<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================================
if(isset($_GET['act'])&&isset($_GET['id']))
{
	$sql="delete from ".ADMIN." WHERE id='$_GET[id]'";
	$db->query($sql);
}

//-----------------------------------------------------
if(isset($_GET['group_id']))
	$subsql=" and a.group_id='$_GET[group_id]'";
if($_SESSION['province'])
	$subsql.=" and a.province='$_SESSION[province]'";
if($_SESSION['city'])
	$subsql.=" and a.city='$_SESSION[city]'";
	
$sql="SELECT a.id, a.user, a.group_id,a.province,a.city,a.lang,g.group_name,a.name FROM ".ADMIN." a
	  LEFT JOIN ".GROUP." g ON a.group_id = g.group_id
	  WHERE id!=1 $subsql order by a.id desc";
$db->query($sql);
$users = $db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo lang_show('admin_system');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('admin_manager');?></div>
	<div class="bigboxbody">
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
	<tr class="theader"> 
		<td width="47">ID</td>
		<td width="212"><?php echo lang_show('actuser');?></td>
		<td width="247"><?php echo lang_show('username');?></td>
		<td width="230" align="left"><?php echo lang_show('usergroup');?></td>
		<td width="140" align="left">业绩</td>
		<td width="121" align="left"><?php echo lang_show('operation');?></td>
	</tr>
	<?php
		while(list($key,$item) = @each($users))
		{
			$sql="select count(*) as num from ".ALLUSER." where invite='".$item['name']."'";
			$db->query($sql);
			$yiji=$db->fetchField('num');//总业绩
			
			$sql="select count(*) as reguser from ".ALLUSER." where TO_DAYS(NOW())-TO_DAYS(regtime)=0 and invite='$item[name]'";
			$db->query($sql);
			$rs=$db->fetchRow();
			$reguser=$rs['reguser'];//今日业绩
		
			echo '<tr>
					<td>'.$item['id'].'</td>
					<td>'.$item['user'].'</td>
					<td>'.$item['name'].'</td>
					<td>'.$item['group_name'].'</td>
					<td><a href="member.php?ordrby=lastLoginTime&category=user&Submit=%CB%D1%CB%F7&invite='.$item['name'].'">'.$reguser.'/'.$yiji.'</a></td>
					<td><a href="add_admin_manager.php?act=edit&id='.$item['id'].'">'.$editimg.'</a> <a href="admin_manager.php?act=del&id='.$item['id'].'">'.$delimg.'</a></td>
				</tr>';
		}
	?>
</table>
</div>
</div>
</body>
</html>