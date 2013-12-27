<?php
//==================================
include_once("$config[webroot]/module/activity/includes/plugin_activity_class.php");

if($_POST['act']=="add")
{
	$activity=new activity();
	$flag=$activity->save_activity_product();
	msg($config['weburl']."/main.php?m=activity&s=admin_activity_product_list&id=$_GET[id]");
}

//----如果申请表中已经存在则显示申请的数据
$sql="select a.product_id,a.status,b.pname,b.price,b.amount,b.pic from ".ACTIVITYPRODUCT." a left join ".PRO." b on a.product_id=b.id where activity_id='$_GET[id]' and member_id='$buid' ";
$db->query($sql);
$ad=$db->getRows();
$tpl->assign("ad",$ad);

include_once("includes/page_utf_class.php");
$sql="select id,pname,pic from ".PRO." where userid='$buid' and promotion_id <> 1 and statu>=1 order by id desc";
//=============================
$page = new Page;
$page->listRows=28;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows=$db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",28";
//=====================
$db->query($sql);
$re["list"]=$db->getRows();
$re["page"]=$page->prompt();

$tpl->assign("pro",$re);

//==================================
$tpl->assign("de",$de);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_activity_product_list.htm");
?>