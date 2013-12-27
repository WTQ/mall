<?php
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//============================================================
if($_POST['submit']=="edit")
{	
	$re=$shop->update_user();
	$admin->msg("main.php?m=shop&s=myshop");
}

$de=$shop->get_shop_info($buid);
$de['con']=$shop->get_shop_detail($buid);
$de['cat']=$shop ->GetShopCatName($de['catid']);
$re=$shop->get_shop_setting($buid);
if($re)
$de=array_merge($de,$re);

if(!is_file($config['webroot']."/uploadfile/phpqrcode/".$buid.".jpg")) 
{	
	include "lib/phpqrcode/phpqrcode.php";
	$value=$config['weburl']."/shop.php?uid=".$buid;
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 4;
	QRcode::png($value,$config['webroot']."/uploadfile/phpqrcode/".$buid.".jpg", $errorCorrectionLevel, $matrixPointSize);
}

$tpl->assign("de",$de);
$tpl->assign("buid",$buid);
$tpl->assign("prov",GetDistrict());

//==================================
$nocheck=true;
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_myshop.htm");
?>