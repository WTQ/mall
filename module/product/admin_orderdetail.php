<?php


if(!empty($_POST['time_expand'])&&!empty($_POST['oid'])&&is_numeric($_POST['oid']))
{
	$sql="update ".ORDER." set time_expand ='1' where order_id='$_POST[oid]'";
	$db->query($sql);
}

include_once("$config[webroot]/module/product/includes/plugin_order_class.php");
$order=new order();
//====================应用支付结果==============
if(!empty($_GET['statu'])&&$_GET['statu']==1)
{
	if($_GET['auth']!=md5($config['authkey']))
		die('参数错误');
		
	$sql="update ".ORDER." set status='2',uptime=".time()." where order_id='$_GET[id]'";
	$db->query($sql);
	//---------------------付款成功减库存，
	$sql="select pid,num,setmeal from ".ORPRO." where order_id='$_GET[id]'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $val)
	{
		if($val['setmeal'])
		{
			if(!empty($val['num']))
			{
				$sql="update ".SETMEAL." set  stock=stock-$val[num] where id=$val[setmeal]";
				$db->query($sql);
			}
		}
		else
		{
			if(!empty($val['num']))
			{
				$sql="update ".PRO." set  amount=amount-$val[num] where id=$val[pid]";
				$db->query($sql);
				
				$sql="select amount from ".PRO." where id='$val[pid]'";
				$db->query($sql);
				if($db->fetchField('amount')<=0)
				{
					$sql="update ".PRO." set  statu=-2 where id=$val[pid]";
					$db->query($sql);
				}
				
			}
		}
	}
	
}
//----------------------------------------------------
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
{
	$tpl->assign("de",$order->orderdetail($_GET['id']));
}
//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_orderdetail.htm");
?>