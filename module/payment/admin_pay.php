<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();
//============================================================
$re=$payment->payment_base();
$tpl->assign("account",$re);
//----------------
$order_id=$_GET['order_id'];
//----------------当前流水详情	
$sql="select * from ".CASHFLOW." where order_id='$order_id' and pay_uid='".$payment->pay_uid."'";
$db->query($sql);
$de=$db->fetchRow();
$tpl->assign("de",$de);
//----------------进行支付,且必须是新订单
if($_POST['submit']&&$de['statu']==1)
{
	if($re['pay_pass']==md5(trim($_POST['pay_pass']))&&!empty($_POST['pay_pass']))
	{
		//-------------减少账户金额
		if($de['price']<0) $de['price']*=-1;
		if($re['cash']<$de['price'])
		{
			$price=$de['price']-$re['cash'];
			$admin->msg("main.php?m=payment&s=admin_accounts_pay&price=$price",'支付金额不足','failure');
		}
		else
		{
			$sql = "update ".PUSER." set cash=cash-".$de['price']." where pay_uid='$re[pay_uid]'";
			$db->query($sql);
			
			//直接到账，修改流水状态,直接标记为成功
			if($de['type']==1)
			{		
				$sql="update ".CASHFLOW." set statu='4' where order_id='$order_id'";
				$db->query($sql);
				
				$sql = "update ".PUSER." set cash=cash+".($de['price']*-1)." where email='$de[seller_email]'";
				$db->query($sql);
			}
			//如果是担保接口，标记为已付款。
			if($de['type']==2)
			{
				$sql="update ".CASHFLOW." set statu='2' where order_id='$order_id'";
				$db->query($sql);
			}
			//--异步处理,以后处理
			
			
			
			//返回同步处理结果
			$url=$de['return_url']."&order_id=$order_id&price=".($de['price']*-1)."&extra_param=$de[extra_param]&statu=1&auth=".md5($config['authkey']);
			msg($url);
		}
	}
	else
	{
		$admin->msg("main.php?m=payment&s=admin_pay&order_id=$order_id",'支付密码错误','failure');
	}
}
//============================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_pay.htm");
?>