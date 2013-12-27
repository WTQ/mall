<?php
//==========================
include_once("$config[webroot]/includes/smarty_config.php");
include_once("$config[webroot]/module/$_GET[m]/includes/includes_modules_class.php");
$md= new includes_modules();

if(!empty($_GET['id']))
{
	$sql="select * from ".SPE." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
if(!empty($_GET['deid']))
{
	$sql="delete from ".MLAY." where id='$_GET[deid]'";
	$db->query($sql);
}
if(!empty($_POST['submit']))
{
	$sql="insert into ".MLAY." (`type`,tid,title,layout,name) values ('1','$_GET[id]','$_POST[title]','$_POST[layout]','$_POST[name]')";
	$db->query($sql);
	msg('module.php?m=special&s=special_con.php&id='.$_GET['id']);
}
if(!empty($_POST['update']))
{
	foreach($_POST['id'] as $key=>$v)
	{
		$sql="update ".MLAY." set nums='".$_POST['nums'][$key]."' where id='$v'";
		$db->query($sql);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
</HEAD>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('hr_info_manager');?></div>
<form action="" method="post" enctype="multipart/form-data">
<div class="bigbox">
	<div class="bigboxhead">模块布局</div>
	<div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="12%" height="44">标题</td>
    <td width="88%"><input class="text" name="title" type="text"></td>
    </tr>
  <tr>
    <td width="12%" height="44">布局位置</td>
    <td width="88%">
      <select class="select" name="layout" id="layout">
      <?php
	  if(!empty($de['layout']))
	  {
		$ly=explode(",",$de['layout']);
		foreach($ly as $v)
			echo '<option value="'.$v.'">'.$v.'</option>';
	  }
	  ?>
        </select></td>
    </tr>
  <tr>
    <td height="46">模块名</td>
    <td>
      <select class="select" name="name" id="name">
      <?php
	  $mds=$md->get_modules();
	  foreach($mds as $v)
	  {
	  	echo '<option value="'.$v.'">'.$v.'</option>';
	  }
	  ?>
      </select>
    </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input class="btn" type="submit" name="submit" id="submit" value="增加">
    </label></td>
  </tr>
</table>

    </div>
  </div>  
<div class="bigbox" style="margin-top:5px;">    
    <div class="bigboxhead">模块列表</div>
	<div class="bigboxbody">
    
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr >
    <td width="13%">排序</td>
    <td width="13%">标题</td>
    <td width="32%">模块</td>
    <td width="34%">布局位置</td>
    <td width="8%">操作</td>
  </tr>
  <?php
  	$sql="select * from ".MLAY." where type=1 and tid='$_GET[id]' order by nums asc,layout asc";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
  ?>
  <tr>
    <td><input name="nums[]" value="<?php echo $v['nums'];?>" type="text" size="5"><input name="id[]" type="hidden" value="<?php echo $v['id'];?>"></td>
    <td><?php echo $v['title'];?></td>
    <td><?php echo $v['name'];?>(<?php echo $v['template'];?>)</td>
    <td><?php echo $v['layout'];?></td>
    <td>
	<a href="module.php?m=special&s=special_con.php&deid=<?php echo $v['id'];?>&id=<?php echo $de['id'];?>"><?php echo $delimg;?></a>
	<a href="module.php?m=special&s=modules_con_set.php&type=<?php echo $v['name'];?>&id=<?php echo $v['id'];?>"><?php echo $setimg;?></a>
	</td>
  </tr>
  <?php 
  }
  ?>
    <tr>
    <td colspan="5"><label>
      <input class="btn" type="submit" name="update" id="update" value="更新顺序">
    </label></td>
    </tr>
</table>

    </div>
    
</div>


</form>
</body>
</html>