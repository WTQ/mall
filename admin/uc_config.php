<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=========================================
if(!empty($_POST['submit']))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='uc'";
		$db->query($sql);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='uc'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','uc')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	
	$write_config_con_str='<?php $uc_config = unserialize(\''.$write_config_con_str.'\');foreach($uc_config as $key=>$v) define($key,$v);?>';//生成要写的内容
	$fp=fopen('../config/uc_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	msg("uc_config.php");
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='uc'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		$index=$v['index'];
		$value=$v['value'];
		$configs[$index]=$value;
	}
	return $configs;
}
//-------------------------------------------------
$uc_config=read_config();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('uc_config_mang');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('uc_config_mang');?></div>
	<div class="bigboxbody">
	<form name="sysconfig" action="uc_config.php" method="post" style="margin-top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="theader">
                <td colspan="2"><?php echo lang_show('uc_config_msg');?></td>
              </tr>
              <tr>
                <td  class="body_left">UC_CONNECT</td>
                <td width="85%">
                  <input class="text" name="UC_CONNECT" type="text" value="<?php echo $uc_config['UC_CONNECT'];?>" id="UC_CONNECT" /></td>
              </tr>
              <tr>
                <td>UC_DBHOST</td>
                <td><input class="text" name="UC_DBHOST" type="text" value="<?php echo $uc_config['UC_DBHOST'];?>" id="UC_DBHOST"></td>
              </tr>
              <tr>
                <td>UC_DBUSER</td>
                <td><input class="text" name="UC_DBUSER" type="text" value="<?php echo $uc_config['UC_DBUSER'];?>" id="UC_DBUSER"></td>
              </tr>
              <tr>
                <td>UC_DBPW</td>
                <td><input class="text" name="UC_DBPW" type="text" value="<?php echo $uc_config['UC_DBPW'];?>" id="UC_DBPW"></td>
              </tr>
              <tr>
                <td>UC_DBNAME</td>
                <td><input class="text" name="UC_DBNAME" type="text" value="<?php echo $uc_config['UC_DBNAME'];?>" id="UC_DBNAME"></td>
              </tr>
              <tr>
                <td>UC_DBCHARSET</td>
                <td><input class="text" name="UC_DBCHARSET" type="text" value="<?php echo $uc_config['UC_DBCHARSET'];?>" id="UC_DBCHARSET"></td>
              </tr>
              <tr>
                <td>UC_DBTABLEPRE</td>
                <td><input class="text" name="UC_DBTABLEPRE" type="text" value="<?php echo $uc_config['UC_DBTABLEPRE'];?>" id="UC_DBTABLEPRE"></td>
              </tr>
              <tr>
                <td>UC_DBCONNECT</td>
                <td><input class="text" name="UC_DBCONNECT" type="text" value="<?php echo $uc_config['UC_DBCONNECT'];?>" id="UC_DBCONNECT"></td>
              </tr>
              <tr>
                <td>UC_KEY</td>
                <td><input class="text" name="UC_KEY" type="text" value="<?php echo $uc_config['UC_KEY'];?>" id="UC_KEY"></td>
              </tr>
              <tr>
                <td>UC_API</td>
                <td><input class="text" name="UC_API" type="text" value="<?php echo $uc_config['UC_API'];?>" id="UC_API"></td>
              </tr>
              <tr>
                <td>UC_CHARSET</td>
                <td><input class="text" name="UC_CHARSET" type="text" value="<?php echo $uc_config['UC_CHARSET'];?>" id="UC_CHARSET"></td>
              </tr>
              <tr>
                <td>UC_IP</td>
                <td><input class="text" name="UC_IP" type="text" value="<?php echo $uc_config['UC_IP'];?>" id="UC_IP"></td>
              </tr>
              <tr>
                <td>UC_APPID</td>
                <td><input class="text" name="UC_APPID" type="text" value="<?php echo $uc_config['UC_APPID'];?>" id="UC_APPID"></td>
              </tr>
              <tr>
                <td>UC_PPP</td>
                <td><input class="text" name="UC_PPP" type="text" value="<?php echo $uc_config['UC_PPP'];?>" id="UC_PPP"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"></td>
              </tr>
            </table>
	</form>
	</div>
</div>
</body>
</HTML>