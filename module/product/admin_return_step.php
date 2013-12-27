<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
//=====================================================================
if($_POST['submit'] =='add')
{
	$order->add_talk();
	$admin->msg("main.php?m=product&s=admin_return_step&id=$_POST[oid]");
}
if($_GET['op'] == "agree")
{
	$order->update_return("agree");
	$admin->msg("main.php?m=product&s=admin_sellorder&status=6");
}
if($_GET['op'] == "refuse")
{	
	//不同意的话直接打回发货状态，
	$order->update_return("refuse");
	$admin->msg("main.php?m=product&s=admin_sellorder&status=5");
}

$tpl->assign("de",$de=$order->get_return());
$tpl->assign("talk",$de=$order->get_talk());
$tpl->assign("pro",$order->get_return_goods($de['id']));
$tpl->assign("buid",$buid);
//=====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_return_step.htm");
?>