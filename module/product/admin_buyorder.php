<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//=======================================

if(isset($_GET['flag'])&&isset($_GET['id']))
{
	if($_GET['flag']==4)
		$order->set_order_statu($_GET['id'],4);//确认收货
		
	if($_GET['flag']==0)
		$order->set_order_statu($_GET['id'],0);//取消定单
}

if(!empty($_GET['deid']))
{
	$order->del_order($_GET['deid']);
}

if(isset($_GET['status']))
	$tpl->assign("blist",$order->buyorder($_GET['status']));
else
	$tpl->assign("blist",$t=$order->buyorder());
//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_buyorder.htm");
?>
