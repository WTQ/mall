<?php
include_once("module/shop/includes/plugin_temp_class.php");
$temp=new temp();
if(isset($_GET['select_tem']))
	$temp->update_user_tem($_GET['select_tem']);
else
{	
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();
	$re=$shop->get_shop_info($buid);
	$tpl->assign("de",$re);
	$tpl->assign("templist",$temp->user_temp_list());
}
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_template.htm");
?>