<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
//=====================================================================

if($_POST['submit']=='return')
{
	$order->add_return();
	$admin->msg("main.php?m=product&s=admin_buyorder&status=5");
}
$tpl->assign("de",$de=$order->orderdetail($_GET['id']));
$tpl->assign("addr",$order->get_return_addr($de['sellerinfo']['userid']));
$tpl->assign("fastmail",$order->get_fastmail());

//=====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_return.htm");
?>