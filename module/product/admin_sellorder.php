<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
include_once("$config[webroot]/includes/page_utf_class.php");
$order=new order();
//=====================================================================
if(isset($_GET['flag'])&&isset($_GET['id'])&&is_numeric($_GET['flag'])&&is_numeric($_GET['id']))
	$order->set_order_statu($_GET['id'],3);

if (isset($_GET['status']))
	$tpl->assign("slist",$order->sellorder($_GET['status']));
else
	$tpl->assign("slist",$de=$order->sellorder());
	
	
//=====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_sellorder.htm");
?>