<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//=======================================

//-----------------ת��֧����ת��ǰ�����������

//-----------------
if(isset($_GET['flag'])&&isset($_GET['id']))
{
	if($_GET['flag']==4)
		$order->set_order_statu($_GET['id'],4);//ȷ���ջ�
		
	if($_GET['flag']==0)
		$order->set_order_statu($_GET['id'],0);//ȡ������
}
if(isset($_GET['status']))
	$tpl->assign("blist",$order->shop_buyorder($_GET['status']));
else
	$tpl->assign("blist",$order->shop_buyorder());

if(!empty($_GET['deid']))
{
	$order->del_order($_GET['deid']);
}
//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_shop_buyorder.htm");
?>