<?php
include_once("module/shop/includes/plugin_links_class.php");
$links=new links();
//===================================================================

if($_POST['submit']=='add')
{	
	$flag=$links->add_link();
	if($flag == 'error')
	{
		$admin->msg('main.php?m=shop&s=admin_link&type=add','参数错误','failure');     
	}
	else
	{
		$admin->msg('main.php?m=shop&s=admin_link');
	}
}

if($_POST['submit']=='edit' and is_numeric($_POST['id']))
{	
	$flag=$links->edit_link($_POST['id']);
	if($flag == "error")
	{
		$admin->msg('main.php?m=shop&s=admin_link&type=edit&id='.$_POST['id'],'参数错误','failure');     
	}
	else
	{
		$admin->msg('main.php?m=shop&s=admin_link');
	}
}
if($_GET['type']=='edit' and is_numeric($_GET['id']))
{
	$link=$links->get_link($_GET['id']);
	$tpl->assign("link",$link);
}
elseif($_GET['type']=='del' and is_numeric($_GET['id']))
{
	$links->del_link($_GET['id']);
	$admin->msg('main.php?m=shop&s=admin_link');
}
elseif(empty($_GET['type']))
{
	$link=$links->link_list();
	$tpl->assign("link",$link);
}

//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_link.htm");
?>