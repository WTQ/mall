<?php
include_once("module/payment/includes/payment_class.php");
$payment=new payment();
//============================================================
if(!empty($_GET['deid']))
{
	$payment->del_cashflow($_GET['deid']*1);
}

$re= $payment->payment_cashflow();
$earning = 0; $pay = 0;
foreach($re['list'] as $v)
{
	if($v['statu']==4)
	{
		if($v['price']<0)
			$pay += $v['price'];
		else
			$earning += $v['price'];
	}
}
$tpl->assign("re",$re);
$tpl->assign("earning",$earning);
$tpl->assign("pay",-$pay);
$tpl->assign("allt",$pay+$earning);
unset($cash);

//================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_accounts_cashflow.htm");
?>