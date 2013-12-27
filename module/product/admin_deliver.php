<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
//=====================================================================

if($_POST['status']=='send')
{
	$order->updateorder();
	$admin->msg("main.php?m=product&s=admin_deliver&status=delivering");
}	
	
//发货
if($_GET['status']=='send')
{
	$tpl->assign("de",$order->orderdetail($_GET['id']));
	$tpl->assign("addr",$order->get_addr());
	$tpl->assign("fastmail",$order->get_fastmail());
}
else
{
	$s=$_GET['status']?($_GET['status']=="delivering"?"3":"4"):"2";
	$tpl->assign("slist",$de=$order->sellorder($s));
}
	
//=====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_deliver.htm");
?>