<?php
include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
$orderadder=new orderadder();
	
//--增加收货地址

if($_POST["submit"]=='add')
{   
	$flag=$orderadder->add_orderadder();
	if($flag==false)
	{
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder','不能超过5条','failure');     
	}
	else if($flag=='error')
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder','参数错误','failure');  
	else
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder');  
}
//--修改收货地址
if($_POST['submit']=='edit')
{
	$flag=$orderadder->edit_orderadder($_POST['edid']); 
	
	if($flag=='error')
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder','参数错误','failure');
	else
		$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder');  
}
//--删除收货地址
if(!empty($_GET['edid'])&&is_numeric($_GET['edid']))
{
	$flag=$orderadder->del_orderadder($_GET['edid']);  
	$admin->msg($config['weburl'].'/main.php?m=member&s=admin_orderadder');
}
//--显示收货地址
if(!empty($_GET['id'])&&is_numeric($_GET['id']))
	$tpl->assign("de",$orderadder->get_orderadder($_GET['id']));
	
$tpl->assign("list",$orderadder->get_orderadderlist());
//---------------------------------
$tpl->assign("config",$config);
$tpl->assign("prov",GetDistrict());
$tpl->assign("lang",$lang);
$output=tplfetch("admin_orderadder.htm");

?>