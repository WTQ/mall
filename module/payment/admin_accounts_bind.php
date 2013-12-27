<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();
//============================================================

if($_POST["submit"] == 'add')
{	//新增银行
	$payment->payment_bind();
	$admin->msg('main.php?m=payment&s=admin_accounts_bind');
}
/*if($_GET['deid'])
{
	$deid=$_GET['deid']*1;
	$sql="delete from ".ACCOUNTS." where id='$deid'";
	$db->query($sql);
}*/
$bank_list=$payment->get_bind_bank();
$tpl->assign("bank",$bank_list[0]);

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_accounts_bind.htm");
?>