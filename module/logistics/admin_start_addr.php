<?php
//===================================================================
include_once($config['webroot']."/module/logistics/includes/logistics_class.php");
$logistics=new logistics();

//---添加
if($_POST['action']=='save')
{
	$logistics->add_lgsaddr();
	$admin->msg('main.php?m=logistics&s=admin_start_addr');
}
//---修改
if($_POST['action']=='edit')
{
	$logistics->edit_lgsaddr();
	$admin->msg('main.php?m=logistics&s=admin_start_addr');
}
//---删除
if(!empty($_GET['delid']))
	$logistics->del_lgsaddr($_GET['delid']);
	
//---详情
if(!empty($_GET['id']))
{
	$de=$logistics->lgsaddr_detail($_GET['id']);
	$tpl->assign("de",$de);
}

//---发货地址选定
if(!empty($_GET['fid']))
	$logistics->set_lgsaddr(1,$_GET['fid']); 

//---退货地址选定
if(!empty($_GET['tid']))
	$logistics->set_lgsaddr(2,$_GET['tid']);

$tpl->assign("info",$logistics->lgsaddr_list());
$tpl->assign("prov",GetDistrict());
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_start_addr.htm");
?>