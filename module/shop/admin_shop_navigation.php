<?php
include_once("module/shop/includes/plugin_temp_class.php");
$temp=new temp();

if($_POST['submit']=='add')
{	
	$flag=$temp->add_shop_nav();
	if($flag=='error')
	{
		$admin->msg('main.php?m=shop&s=admin_shop_navigation&type=add','参数错误','failure');     
	}
	else
	{
		$admin->msg('main.php?m=shop&s=admin_shop_navigation');
	}
}

if($_POST['submit']=='edit' and is_numeric($_POST['id']))
{	
	$flag=$temp->edit_shop_nav($_POST['id']);
	if($flag=='error')
	{
		$admin->msg('main.php?m=shop&s=admin_shop_navigation&type=edit&id='.$_POST['id'],'参数错误','failure');     
	}
	else
	{
		$admin->msg('main.php?m=shop&s=admin_shop_navigation');
	}
}

if($_GET['type']=='edit' and is_numeric($_GET['id']))
{
	$nav=$temp->get_shop_nav($_GET['id']);
	$tpl->assign("nav",$nav);
}
elseif($_GET['type']=='del' and is_numeric($_GET['id']))
{
	$temp->del_shop_nav($_GET['id']);
	$admin->msg('main.php?m=shop&s=admin_shop_navigation');
}
elseif(empty($_GET['type']))
{
	$nav=$temp->get_shop_nav_list();
	$tpl->assign("nav",$nav);
}

//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_shop_navigation.htm");
?>