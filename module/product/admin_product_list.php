<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");
$pro=new pro();
//============================================================================
if(!empty($deid))
{
	$pro->del_pro($deid);
}
elseif(isset($_GET['update']))
{
	$pro  -> update_pro($_GET['update']);
}
if(!empty($_GET['cstatu'])&&!empty($_GET['pid']))
	$pro->set_pro_statu($_GET['pid'],$_GET['cstatu']);
	

$tpl->assign("re",$pro->pro_list(1));
//==================================
$nocheck=true;
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_product_list.htm");
?>