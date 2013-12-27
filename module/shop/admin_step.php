<?php
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();

//============================================================
if($_POST['submit']=="edit")
{	
	$re=$shop->update_user();
	$admin->msg("main.php?m=shop&s=myshop");
}
//===================================================================
$sql="select * from ".SHOPGRADE." where status='1'";
$db->query($sql);
$re=$db->getRows();
$tpl->assign("re",$re);
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$tpl->assign("cat",$shop->GetShopCatList());
if(isset($_GET['grade']) and is_numeric($_GET['grade']))
{
	$tpl->assign("prov",GetDistrict());
	$output=tplfetch("admin_step1.htm",$flag,true);
}
else
	$output=tplfetch("admin_step.htm",$flag,true);

?>