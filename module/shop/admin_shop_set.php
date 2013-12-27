<?php
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//===================================================================
if($_POST['submit']=="edit")
{	
	$re=$shop->update_slide();
	$admin->msg('main.php?m=shop&s=admin_shop_set');
}
$re=$shop->get_slide();
$tpl->assign("re",$re);
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_shop_setting.htm");
?>