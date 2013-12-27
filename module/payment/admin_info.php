<?php
//============================================================
if(!empty($_POST['email'])&&!empty($_POST['submit']))
{
	$sql = "select pay_uid from ".PUSER." where email='$_POST[email]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(!$re['pay_uid'])
	{	
		$pay_pass=md5(trim($_POST['pay_pass']));
		$sql="insert into ".PUSER." (userid,email,name,tell,mobile,pay_pass) 
		values ('$buid','$_POST[email]','$_POST[name]','$_POST[tell]','$_POST[mobile]','$pay_pass')";
		$db->query($sql);
	}
}
//============================================================
include_once("module/payment/includes/payment_class.php");
$payment=new payment();

//-----------------------------------更新账号信息
if(!empty($_POST['submit'])&&empty($_POST['email']))
{
	$payment->update_account();
	$admin->msg("main.php?m=payment&s=admin_info");
}

//-------------------------------------获取账户信息
$re=$payment->get_payment_statu();
if($re['pay_uid'])
{
	$tpl->assign("de",$re);
}
else
{
	$sql="select * from ".ALLUSER." where userid='$buid'";
	$db->query($sql);
	$de=$db->fetchRow();
	$tpl->assign("de",$de);
	$tpl->assign("first",1);
}
//============================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_info.htm");
?>