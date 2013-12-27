<?php

include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//===========================================================
if($_POST["submit"] == 'edit')
{	
	$shop->Certification();
	$admin->msg('main.php?m=shop&s=admin_certification');
}
//获取认证状态
$tpl->assign("de",$shop->GetCertification());
//=============================================================

$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_certification.htm");
?>