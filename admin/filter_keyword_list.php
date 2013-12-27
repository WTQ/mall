<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);

@include_once("auth.php");
include_once("../includes/page_utf_class.php");
//=======================================
$keyword=isset($_GET['keyword'])?$_GET['keyword']:NULL;

if(!empty($_POST['submit'])&&is_array($_POST['de']))
{
	$id=implode(",",$_POST['de']);
	$sql="delete from ".FILTER." where id in ($id)";
	$db->query($sql);
	update_cache_filter();
}
if(!empty($_GET['id']))
{
    $sql="delete from ".FILTER." where id=".$_GET['id'];
	$db->query($sql);
	update_cache_filter();
}
function update_cache_filter()
{
	global $db;
	$sql="select * from ".FILTER;
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		if($v['statu']==1)
		{	
			if(empty($banned))
				$banned="$v[keyword]";
			else
				$banned.="|$v[keyword]";
		}
		else
		{
			if(empty($find))
			{
				$find="'/$v[keyword]/i'";
				$replace="'$v[replace]'";
			}
			else
			{
				$find.=",'/$v[keyword]/i'";
				$replace.=",'$v[replace]'";
			}
		}
	}
	if(strlen($banned)>0)
		$banned='/('.$banned.')/i';
	else
		$banned='';
	$str='<?php
	$find= array('.$find.');
	$replace=array('.$replace.');
	$banned=\''.$banned.'\';
	$_CACHE[\'word_filter\'] = Array
	(
		\'filter\' => Array
		(
			\'find\' => &$find,
			\'replace\' => &$replace
		),
		\'banned\' => &$banned
	);
	?>';
	$fp=fopen('../config/filter.inc.php','w');
	fwrite($fp,$str,strlen($str));//将内容写入文件．
	fclose($fp);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE> <?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>

<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("de[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("de[]")[j].checked==true)
	  	  document.getElementsByName("de[]")[j].checked = false;
		else
		  document.getElementsByName("de[]")[j].checked = true;
	 }
}
</script>

<div class="bigbox">
	<div class="bigboxhead">关键词管理</div>
	<div class="bigboxbody">
	<form name="form" action="" method="get">
<table width="99%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td class="searh_left">关键词</td>
    <td><input class="text" name="keyword" type="text" value="<?php echo $keyword; ?>" size="40">
      <input class="btn" type="submit" name="Submit" value=" <?php echo lang_show('submit')?> " /></td>
  </tr>
</table>
</form>
<form name="form" action="" method="post">
	  <table width="100%" border="0" align="left" cellpadding="2" cellspacing="0" >
		<tr class="theader"> 
		  <td width="31"><input onClick="do_select()" name="" type="checkbox" class="checkbox"></td>
		  <td width="124">词组</td>
		  <td width="231">替换</td>
		  <td width="285"><?php echo lang_show('statu')?></td>
		  <td width="229"><?php echo lang_show('time')?></td>
		  <td width="99" align="center"><?php echo lang_show('option')?></td>
		</tr>
		<?php
		$tsql=NULL;
		if($keyword)
			$tsql.=" and keyword like '%$_GET[keyword]%'"; 
		$sql="select * from ".FILTER." where 1 $tsql order by id desc";
		//=============================
		$page = new Page;
		$page->listRows=50;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",50";
		$pages = $page->prompt();
		//================================
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $va)
		{
		?> 
		<tr> 
		  <td><input name="de[]" id="de" type="checkbox" class="checkbox" value="<?php echo $va['id']; ?>"></td>
		  <td><?php echo $va['keyword']; ?></td>
		  <td><?php echo $va['replace']; ?>&nbsp;</td>
		  <td><?php if($va['statu']==1) echo '禁止';else echo '替换'; ?>&nbsp;</td>
		  <td><?php echo date("Y-m-d",$va['time']); ?>&nbsp;</td>
		  <td align="center">
		  <a href="?id=<?php echo $va['id'];?>"><?php echo $delimg;?></a>
		  <a href="add_filter_keyword.php?id=<?php echo $va['id'];?>"><?php echo $editimg;?></a>
		  </td>
		</tr>
		<?php 
		}
		?> 
	  <tr>
      <td colspan="2" >
	  <input class="btn" type="submit" name="submit" value="<?php echo lang_show('delete')?>" ></td>
      <td >&nbsp;</td>
      <td colspan="3" ><div class="page"><?php echo $pages?></div></td>
      </tr>
  </table></form>
  </div>
</div>

</body>
</html>