<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();
//=================================
		
	//--------应用充值结果
	$re=$payment->apply_result();
	if($re)
		header("Location:main.php?m=payment&s=admin_accounts_base");
	//--------获取账户基本信息
	$tpl->assign("re",$payment->payment_base());

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_accounts_base.htm");
?>