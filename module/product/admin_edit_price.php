<?php
include_once("module/product/includes/plugin_order_class.php");
$order=new order();
//---------------------------------------------------------
if(!empty($_POST['submit']))
{	
	$order->update_price($_POST['price']*1,$_GET['oid']);
}
//----------------------------------------------------------
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_edit_price.htm",NULL,'true');
?>