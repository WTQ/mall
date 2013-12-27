<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();

//==================================
if(!empty($_POST['amount']))
	$payment->online_pay();
$tpl->assign("re",$payment->payment_pay());

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_accounts_pay.htm");
?>