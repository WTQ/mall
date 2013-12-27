<?php
	include_once("$config[webroot]/module/shop/includes/plugin_temp_class.php");
	$temp=new temp();
	
	if(is_numeric($_GET['id']))
	{
		$tpl->assign("re",$temp->get_shop_nav_content($_GET['id']));
	}
	//------------------------------------Seo config
	$shopconfig["hometitle"]="信用评价".'-'.$shopconfig["hometitle"];
	$shopconfig["homedes"]="信用评价".','.$shopconfig["homedes"];
	$shopconfig["homekeyword"]="信用评价".','.$shopconfig["homekeyword"];
	//====================================================================
	$tpl->assign("lang",$lang);
	$tpl->assign("config",$config);
	$tpl->assign("com",$company);
	$output=tplfetch("space_public.htm",$flag);
?>