<?php
//支付网关
include_once("../../includes/global.php");
include_once("../../includes/smarty_config.php");
//============================================================
if($_GET['auth']!=md5($config['authkey']))
	die('非法请求');

//----------获取post数据，进行处理
$order_id=$_GET['order_id'];	//外部订单号
$price=$_GET['price'];			//订单价格
$return_url=$_GET['return_url'];
$notify_url=$_GET['notify_url'];//异步处理返回请求地址
$note=$_GET['note'];			//订单内容描述
$extra_param=$_GET['extra_param'];//自定义扩展参数
$action=$_GET['action'];		//动作指令
$statu=$_GET['statu'];			//担保接口状态值
$type=$_GET['type'];			//支付类型，1为直接到账，2为担保支付结口

$seller_email=$_GET['seller_email'];//卖家信息获取
if(!empty($seller_email))
{
	$sql="select email,pay_uid from ".PUSER." where userid='$seller_email' or email='$seller_email'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re)
	{
		$seller_email=$re['email'];
		$seller_id=$re['pay_uid'];
	}
	else
	{
		echo -1;
		die;
	}
}
$buyer_email=$_GET['buyer_email'];//可能通过参数传入购买者信息
if(!empty($buyer_email))
{
	$sql="select email,pay_uid from ".PUSER." where userid='$buyer_email' or email='$buyer_email'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re)
	{
		$buyer_email=$re['email'];
		$buyer_id=$re['pay_uid'];
	}
	else
	{
		header("Location:$config[weburl]/main.php?m=payment&s=admin_info");
		echo -2;
		die;
	}
}
if(empty($seller_email))
	die('卖家信息不完整');
if(empty($buyer_email))
	die('买家信息不完整');
//----------------------------------------------------------------

switch ($action)
{
	case "add":
	{
		//-----------------写入流水表，仅写入一次
		$sql="select order_id from ".CASHFLOW." where order_id='$order_id'";
		$db->query($sql);
		if(!$db->fetchField('order_id'))
		{
			$flow_id=date("Ymdhis").rand(0,9);
			$time=time();
			
			//写入买家流水信息
			$sql="insert into ".CASHFLOW." 
				(pay_uid,buyer_email,seller_email,flow_id,order_id,price,time,note,statu,return_url,notify_url,extra_param,type) 
			values 
				('$buyer_id','','$seller_email','$flow_id','$order_id','".($price*-1)."','$time','$note','1','$return_url','$notify_url','$extra_param','$type')";
			$re=$db->query($sql);

			//写入卖家流水信息
			$sql="insert into ".CASHFLOW." 
			(pay_uid,buyer_email,seller_email,flow_id,order_id,price,time,note,statu,return_url,notify_url,extra_param,type) 
			values 
			('$seller_id','$buyer_email','','$flow_id','$order_id','$price','$time','$note','1','$return_url','$notify_url','$extra_param','$type')";
			$re=$db->query($sql);
		}
		if($extra_param=='Commission')
		{
			$sql="update ".CASHFLOW." set statu='4' where order_id='$order_id'";
			$db->query($sql);
			
			$sql = "update ".PUSER." set cash=cash-$price where email='$buyer_email'";
			$db->query($sql);
			
			$sql = "update ".PUSER." set cash=cash+$price where email='$seller_email'";
			$db->query($sql);
			break;
		}
		msg("$config[weburl]/main.php?m=payment&s=admin_pay&order_id=$order_id");//转向支付页面
		break;
	}
	case "update":
	{
		if($statu==0)
		{
			//=======如果已付款的情况下，要对买家加钱。
			$sql="select price,statu from ".CASHFLOW." where pay_uid='$buyer_id' and order_id='$order_id'";
			$db->query($sql);
			if($db->fetchField('statu')==2)
			{
				$price=$db->fetchField('price');
				if($price<0)
					$price*=-1;
				$sql = "update ".PUSER." set cash=cash+$price where email='$buyer_email'";
				$re=$db->query($sql);
			}
			//=======取消定单
			$sql="update ".CASHFLOW." set statu='0' where order_id='$order_id'";
			$re=$db->query($sql);
			
		}
		if($statu==3)
		{	//发货，更改订单状态
			$sql="update ".CASHFLOW." set statu='3' where order_id='$order_id'";
			$re=$db->query($sql);
		}
		if($statu==4)
		{
			//确认收货，更改状态
			$sql="update ".CASHFLOW." set statu='4' where order_id='$order_id'";
			$re=$db->query($sql);
			//卖家加钱
			$sql="select price from ".CASHFLOW." where pay_uid='$seller_id' and order_id='$order_id'";
			$re=$db->query($sql);
			$price=$db->fetchField('price');
			if($price<0)
				$price*=-1;
			$sql = "update ".PUSER." set cash=cash+$price where email='$seller_email'";
			$re=$db->query($sql);
		}
		if($statu==5)
		{
			//审请退货，退货中。
			$sql="update ".CASHFLOW." set statu='5' where order_id='$order_id'";
			$re=$db->query($sql);
		}
		if($statu==6)
		{
			//退货成功，更改状态
			$sql="update ".CASHFLOW." set statu='6' where order_id='$order_id'";
			$re=$db->query($sql);
			
			//买家加钱
			$sql="select price from ".CASHFLOW." where pay_uid='$buyer_id' and order_id='$order_id'";
			$re=$db->query($sql);
			$price=$db->fetchField('price');
			if($price<0)
				$price*=-1;
			$sql = "update ".PUSER." set cash=cash+$price where email='$buyer_email'";
			$re=$db->query($sql);
		}
		
		//========返回执行结果
		if($re)
		{
			$return['statu']='true';
			$return['auth']=md5($conig['authkey']);
		}
		else
		{
			$return['statu']='false';
			$return['auth']=md5($conig['authkey']);
		}
		echo json_encode($return);
		
		break;
		
	}
	case "reprice":
	{
		//买家流水
		$sql="update ".CASHFLOW." set 
			price=price+".($price*-1)." where order_id='$order_id' and seller_email='$seller_email'";
		$re1=$db->query($sql);
		
		//卖家流水
		$sql="update ".CASHFLOW." set 
			price=price+$price where order_id='$order_id' and buyer_email='$buyer_email'";
		$re3=$db->query($sql);
		
		//========返回执行结果
		if($re1&&$re2)
		{
			$return['statu']='true';
			$return['auth']=md5($conig['authkey']);
		}
		else
		{
			$return['statu']='false';
			$return['auth']=md5($conig['authkey']);
		}
		echo json_encode($return);
		
		break;
	}
	default:
	{
		break;
	}
	
}
?>