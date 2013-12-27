<?php
//-----------------------------------应用支付结果
if($buid&&$_GET['oid']*1)
{
	$tm=time();
	$sql="update ".TGORDER." set status='30',payment_time='$tm' where order_id='$_GET[oid]'";
	$db->query($sql);
	
	//---购买次数加下
	$sql="update ".TG." set sell_amount=sell_amount+(select quantity from ".TGORDER." where order_id='$_GET[oid]') where id=(select tg_id from ".TGORDER." where order_id='$_GET[oid]')";
	$db->query($sql);
	
	msg("main.php?m=tg&s=admin_tg_order");
	
}
//----------------------------------
if($buid&&$_GET['id']*1>0)
{
	$sql ="select * from ".TG." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	$tpl->assign("de",$de);	
	
	//收货地址
	include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
	$orderadder=new orderadder();
	$tpl->assign("listadder",$adlist=$orderadder->get_orderadderlist());
	
	if(!empty($_POST))
	{
		$tm=date('YmdHis',time());
		$order_id=$tm.rand(0,9);
		$tm=time();
		$sql ="select * from ".TG." where id='$_GET[id]'";
		$db->query($sql);
		$de=$db->fetchRow();
		
		$address=$orderadder->get_orderadder($_POST['addressid']);
		
		$sql="insert into ".TGORDER." set order_id='$order_id',tg_name='$de[name]',tg_id='$de[id]',tg_pic='$de[pic]',status='20',member_id='$buid',member_name='$b2bbuilder_auth[1]',contact='$address[name]',address='$address[area] $address[address]',tel='$address[tel]',remark='$_POST[remark]',price ='$de[price]',quantity ='$_POST[quantity]',create_time='$tm'";
		$re=$db->query($sql);
		if($re)
		{
			//------------生成订单，订单完成后对结果进行更新
			include("$config[webroot]/module/payment/lang/$config[language].php");
			$post['type']=1;//直接到账
			$post['action']='add';//
			$post['buyer_email']=$buid;
			$post['seller_email']='admin@systerm.com';
			$post['order_id']=$order_id;//外部订单号
			$post['price']=$de['price']*1*$_POST['quantity']*1;//订单总价，单价元
			$post['return_url']=$config['weburl'].'/?m=tg&s=order&oid='.$order_id.'&id='.$_GET['id'];//返回地址
			$post['notify_url']=$config['weburl'].'/?m=tg&s=order&oid='.$order_id.'&id='.$_GET['id'];//异步返回地址。
			$post['note']=$de['name'];//备注
			pay_get_url($post);//跳转至订单生成页面
			//----------------
		}
	}
}
include_once("footer.php");
$tpl->assign("current","tg");	
$out=tplfetch("tg_order.htm");
?>