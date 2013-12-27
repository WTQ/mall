<?php
include_once("$config[webroot]/module/member/includes/plugin_invoice_class.php");
$invoice=new invoice();
	
//--增加发票信息
if($_POST["submit"]=='add')
{   
	$flag=$invoice->add_invoice();
	if($flag==false)
	{
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_invoice','不能超过5条','failure');     
	}
	else if($flag=='error')
	{
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_invoice','参数错误','failure'); 
	}
	else
	{
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_invoice');     
	}
}
//--修改发票信息
if($_POST["submit"]=='edit')
{
	$flag=$invoice->edit_invoice($_POST['id']); 
	if($flag=='error')
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_invoice&id='.$_GET['id'],'参数错误','failure');  
	else
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_invoice');   
}
//--删除发票信息
if(!empty($_GET['edid'])&&is_numeric($_GET['edid']))
	$flag=$invoice->del_invoice($_GET['edid']);  
//--默认发票
if(!empty($_GET['did'])&&is_numeric($_GET['did']))
	$flag=$invoice->edit_default($_GET['did']);  
//--显示发票信息
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
	$tpl->assign("de",$invoice->get_invoice($_GET['id']));
	
$tpl->assign("invoice",$invoice->get_invoicelist());
//---------------------------------
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_invoice.htm");

?>