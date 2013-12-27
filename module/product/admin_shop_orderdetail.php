<?php
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//==============================
if(!empty($_GET['statu'])&&$_GET['statu']==1)
{
	$order->set_order_statu($_GET['id'],2);
	//---------------------¸¶¿î³É¹¦¼õ¿â´æ£¬
	$sql="select pid,num  from ".ORPRO." where order_id='$_GET[id]'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $val)
	{
		if(!empty($val['num']))
		{
			$sql="update ".PRO." set  amount=amount-$val[num] where id=$val[pid]";
			$db->query($sql);
		}
	}
	//----------------------
}
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
{
	$tpl->assign("de",$order->shop_orderdetail($_GET['id']));
}
//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_shop_orderdetail.htm");
?>