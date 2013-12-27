<?php
//----------------------------------------开通的物流类型
$config['lgs_type']=array('express'=>'快递','ems'=>'EMS','mail'=>'平邮');
//==============================================================
if(!empty($_POST['submit']))
{	
	include($config['webroot']."/module/logistics/includes/logistics_class.php");
	$logis=new logistics();
	if(empty($_GET['edit']))
		$re=$logis->add_logis();
	else
		$re=$logis->edit_logis($_GET['edit']*1);
	$admin->msg("main.php?m=logistics&s=admin_logistics_temp");
}
//----------------------------------------Get_detail
$sql="select * from ".LGSTEMP." where id='$_GET[edit]'";
$db->query($sql);
$de=$db->fetchRow($sql);

$sql="select * from ".LGSTEMPCON." where temp_id='$de[id]' and define_citys='default' order by id asc";
$db->query($sql);
$k=$db->getRows();
foreach($k as $v)
{
	$de['logistics_default'][]=$v;
	
	$sql="select * from ".LGSTEMPCON." where temp_id='$de[id]' and define_citys!='default' and logistics_type='".$v['logistics_type']."'";
	$db->query($sql);
	$de['detail'][$v['logistics_type']]=$db->getRows();
}
$tpl->assign("de",$de);
//----------------------------------------delete
if($_GET['deid'])
{
	$sql="delete from ".LGSTEMP." where userid='$buid' and id='$_GET[deid]'";
	$db->query($sql);
	$sql="delete from ".LGSTEMPCON." where temp_id='$_GET[deid]'";
	$db->query($sql);
}
//----------------------------------------Get List
$sql="select * from ".LGSTEMP." where userid='$buid'";
$db->query($sql);
$re=$db->getRows();
foreach($re as $key=>$v)
{
	$sql="select * from ".LGSTEMPCON." where temp_id='$v[id]' order by id asc";
	$db->query($sql);
	$re[$key]['detail']=$db->getRows();
}
$tpl->assign("lglist",$re);
//---------------------------------------

$sql="select name,id from ".DISTRICT." where pid=0 order by sorting,id ";
$db->query($sql);
$de=$db->getRows();
foreach($de as $k=>$v)
{
	$sql="select name,id from ".DISTRICT." where pid=$v[id] order by sorting,id ";
	$db->query($sql);
	$de[$k]['city']=$db->getRows();	
}
$tpl->assign("pv",$de);

//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_logistics_temp.htm");
?>