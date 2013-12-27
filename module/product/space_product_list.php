<?php
include_once("module/product/includes/plugin_pro_class.php");
$product=new pro();
//====================================================================
$re=$product->shop_pro_list();
$de=$product->get_user_common_lower_cat();
$tpl->assign("info",$re);
$tpl->assign("cat",$de);
//------------------------------------Seo config
$shopconfig["hometitle"]=$lang['pro_list'].'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]=$lang['pro_list'].','.$shopconfig["homedes"];
$shopconfig["homekeyword"]=$lang['pro_list'].','.$shopconfig["homekeyword"];
//====================================================================
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_product_list.htm",$flag);
?>