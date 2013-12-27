<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================================
if($_SESSION['province']||$_SESSION['city'])
	msg('noright.php');
	
if(isset($_GET["act"])&&isset($_GET["id"]))
{
	$sql="delete from ".GROUP." WHERE group_id='$_GET[id]'";
	$db->query($sql);
}
$sql="SELECT * FROM ".GROUP." WHERE 1 order by group_id desc";
$db->query($sql);
$groups = $db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<link href="main.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('add_group');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr class="theader">
        <td width="42"><?php echo lang_show('group_id');?></td>
        <td width="100"><?php echo lang_show('group_name');?></td>
        <td width=""><?php echo lang_show('descript');?></td>
        <td width="100" align="left"><?php echo lang_show('operate');?></td>
      </tr>
      <?php
	while(list($key,$item) = @each($groups))
	{
		echo '<tr>
				<td>'.$item['group_id'].'</td>
				<td><a href=admin_manager.php?group_id='.$item['group_id'].'>'.$item['group_name'].'</a></td>
				<td>'.$item['group_desc'].'</td>
				<td><a href="group.php?act=edit&id='.$item['group_id'].'">'.$editimg.'</a> <a href="?act=del&id='.$item['group_id'].'">'.$delimg.'</a> </td>
			</tr>';
	}
	?>
    </table>
  </div>
</div>
</body>
</html>
