<?php
include_once("module/shop/includes/plugin_temp_class.php");
$temp=new temp();
//===================================================================

if($_POST['submit']=="add")
{	
	$flag=$temp->add_cs();
	$admin->msg('main.php?m=shop&s=admin_custom_service');
}

if($_POST['op']=="del")
{	
	$flag=$temp->del_cs();die;
}


$tpl->assign("cs",$temp->get_cs());
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_custom_service.htm");
?>