<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();
//=================================
if($_POST["submit"] == 'add')
{
	$flag=$payment->add_pickup();
	if($flag==1)
		$admin->msg('main.php?m=payment&s=admin_accounts_pickup&type=add',"提现金额错误",'failure');
	elseif($flag==2)
		$admin->msg('main.php?m=payment&s=admin_accounts_pickup&type=add',"支付密码错误",'failure');
	else
		$admin->msg('main.php?m=payment&s=admin_accounts_pickup');
}

//------------账户余额
$tpl->assign("re",$payment->payment_base());

//------------已绑定银行
$re=$payment->get_bind_bank(1);

$tpl->assign("bank_list",$re);
if(!$re)
{
	$admin->msg("main.php?m=payment&s=admin_accounts_bind","请先设置提现银行账号",'failure');
}

//------------提现记录
$re=$payment->pickup_list();
$tpl->assign("plist",$re);
//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_accounts_pickup.htm");
?>