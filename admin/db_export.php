<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//======================================================
$backup_base = '/databackup';
define("VERSION",$config['version']);
$times = time();
$pages = 1;
$completed = 0;
$nextdumped = 0;
$currentsize = 0;
$filelist = array();
$currentdata = "";
$fileextend = rand(1000,10000);
$tmp_table = NULL;
if(!is_dir($config['webroot'].$backup_base)){
	echo $backup_base.lang_show('base_is_not_exist');exit;
}

if(!is_writable($config['webroot'].$backup_base)){
	echo $backup_base.lang_show('base_is_not_writable');exit;
}
if($_GET['del'])
{
	$open=opendir($config['webroot'].$backup_base.'/'.$_GET['del']);
	while($exist = readdir($open)) 
	{
		if (is_file($config['webroot'].$backup_base.'/'.$_GET['del']."/".$exist)) 
		{
			unlink($config['webroot'].$backup_base.'/'.$_GET['del']."/".$exist);
		}
		
	}	
	closedir($open);
	@rmdir($config['webroot'].$backup_base.'/'.$_GET['del']);
	admin_msg('db_export.php',"删除成功!!!"); 
}
if($_GET['t'] and $_GET['num'])
{
	mysql_query("SET NAMES utf8");
	if(file_exists($b2bbuilder_backup=$config['webroot'].$backup_base."/".$_GET['t']."/".$_GET['t']."_".$_GET['num'].".php"))
	{
		include($b2bbuilder_backup);
		$num=$_GET['num']+1;
		if(file_exists($config['webroot'].$backup_base."/".$_GET['t']."/".$_GET['t']."_".$num.".php"))
		{
			echo $_GET['t']."_".$_GET['num'].".php 备份成功!!!";
			msg("db_export.php?t=$_GET[t]&num=$num");
		}
		else
		{  
			unset($_SESSION['table']);
			admin_msg('db_export.php',lang_show('db_import_success')); 
		}
	}
}

$url=$config['webroot'].$backup_base.'/';
$open=opendir($url);
if($open)
{ 
	$num=0;
	while($exist=readdir($open))
	{
		if($exist!="."&&$exist!=".."&&$exist!=".svn"&&$exist!="index.htm")
		{
			$totalsize=0;
			$file=opendir($url.$exist);
			while($filename=readdir($file))
			{
				if($filename!="."&&$filename!="..")
				{
					$totalsize+=(int)@filesize($url.$exist.'/'.$filename);
					$uptime=filectime($url.$exist.'/'.$filename);
				}
			}
			$arr[$num]['name']=$exist;
			$arr[$num]['size']=setupSize($totalsize);
			$arr[$num]['time']=$uptime;
			$num++;
		}
	}
		
}

if(isset($_POST['submit']))
{
	$backup_dir = substr(md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'].substr($times, strlen($times)-4, 4)), 8, 6);
	@mkdir($config['webroot'].$backup_base.'/data_'.$backup_dir, 0777);
	
	$sql="SHOW TABLE STATUS";
	$db->query($sql);
	$re=$db->getRows();
	for($i=0;$i<count($re);$i++) 
	{
		dumpTable($re[$i]['Name'],$i);
	}
	$data_end=" ?".">";
	$currentdata .= $data_end;
	dumpFileCreate($currentdata,"w");
	admin_msg('db_export.php',"备份成功!!!"); 
}
function setupSize($fileSize)  
{          
	$size = sprintf("%u",$fileSize);          
	if($size==0)  
	{
		return( "0 Bytes");          
	}          
	$sizename=array("Bytes","KB","MB","GB","TB");        
	return  round($size/pow(1024,($i= floor(log($size,1024)))),2).$sizename[$i];  
}  

function execute($sql)
{
	mysql_query($sql);
}
function create_table($table,$sql)
{
	global $tmp_table;
	if(!$tmp_table)
	{
		do
		{ 
			$tmp_table="b2bbuilder_tmp_".rand(1000,10000);
			$_SESSION['table']=$tmp_table;
		}
		while(@mysql_query("select * from `$tmp_table`"));
	}
	execute("CREATE TABLE `$tmp_table` $sql");
	execute("drop table if exists `$table`");
}
function insert_table($data)
{
	$tmp_table=$_SESSION['table'];
	execute("insert ignore into `$tmp_table` values $data");
}
function clear_table($table)
{
	$tmp_table=$_SESSION['table'];	
	execute("alter table `$tmp_table` rename `$table`");
}
function dumpTable($table,$table_id)
{
	global $db, $nextdumped, $currentsize;
	$db->query("set sql_quote_show_create = 1");
	$db->query("show create table `$table`");
	$row = $db->fetchRow();

	$sql = str_replace("\n","\\n",str_replace("\"","\\\"",$row['Create Table']));
	$sql = preg_replace("/^(CREATE\s+TABLE\s+`$table`)/mis","",$sql);
	$sqlcreate = "create_table(\"$table\",\"$sql\");\r\n\r\n";
	stackData($sqlcreate);
		
	$limitstrart = 0;
	$dumpedrows=$limitoffset=400;
	$sqldumped = "";
	while ($dumpedrows == $limitoffset) {
		$db->query("SELECT * FROM `$table` limit ".$limitstrart.",$limitoffset ");
		$numoffields = $db->num_fields();
		$dumpedrows = $db->num_rows();
		while ($sqlrow = $db->fetch_row()) {
			$sqldumped .= ($sqldumped ? ",\r\n" : "")."(";
			for ($i=0;$i<$numoffields;$i++) {
				if (!isset($sqlrow[$i]) or is_null($sqlrow[$i])) {
					$sqlrow[$i] = "NULL";
				} else {
					$sqlrow[$i] = '\''.escape_string($sqlrow[$i]).'\'';
				}
			}
			$limitstrart++;
			$sqldumped .= implode(",",$sqlrow).")";
			$dumpedlength = strlen($sqldumped);

			if ($dumpedlength > 100000 || ($currentsize+$dumpedlength >= 2000*1000)) {
				$dumpstring = "insert_table(\"$sqldumped\");\r\n\r\n";
				stackData($dumpstring);
				$sqldumped = "";
			}
		}
	}
	if ($sqldumped) {
		$dumpstring = "insert_table(\"\r\n$sqldumped\");\r\n\r\n";
		stackData($dumpstring);
	}
	$dumpstring = "clear_table(\"$table\");\r\n\r\n";
	stackData($dumpstring);
}

function stackData($data) {
	global $currentsize, $currentdata, $pages;
	$currentsize += strlen($data);
	$currentdata .= $data;
	if($currentsize >= 2000*1000){
		$currentsize = 0;
		$currentdata .= "\r\n?".">";
		dumpFileCreate($currentdata, "w");
		$pages++;
	}
}

function dumpFileCreate($data,$method='w') {
	global $config,$backup_base,$backup_dir,$fileextend,$pages,$currentdata;
	$dumpfilename = "data_{$backup_dir}_{$pages}.php";
	$data = "<?\r\nif(!defined('VERSION')) {exit;}\r\n".$data;
	$fp=fopen($config['webroot'].$backup_base."/data_".$backup_dir."/{$dumpfilename}","$method");
	flock($fp,2);
	fwrite($fp,$data);
	$currentdata = "";
}

function escape_string($string) {
	$string = mysql_escape_string($string);
	$string = str_replace('\\\'','\'\'',$string);
	$string = str_replace("\\\\","\\\\\\\\",$string);
	$string = str_replace('$','\$',$string);
	return $string;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('backup_dir');?></div>
	<div class="bigboxbody">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <form action="" method="post">
                <tr class="theader">
                <td><input class="btn" type="submit" name="submit" value="<?php echo lang_show('backup');?>" ></td>
                </tr>
            </form>
        </table>
        <?php if($arr){ ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
		  <?php foreach($arr as $key=>$val){ ?> 
           <tr>
           		<td><?php echo ($key+1);?></td>
                <td><?php echo $val['name']; ?></td>
                <td><?php echo $val['size']; ?></td>
                <td><?php echo date("Y-m-d H:i:s",$val['time']); ?></td>
                <td><a  href="db_export.php?num=1&t=<?php echo $val['name']; ?>">恢复</a></td>
                <td><a  href="db_export.php?del=<?php echo $val['name']; ?>"><?php echo $delimg;?></a></td>
           </tr>
           <?php } ?>
        </table>
        <?php } ?>
	</div>
</div>
</body>
</HTML>
