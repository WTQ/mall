<?php

$fn=$config['webroot'].'/config/shop_data/shop_data_'.$buid.'.txt';
@$fp=fopen($fn,'r');
@$con=fread($fp,filesize($fn));
@fclose($fp);
$tpl->assign("comintro",$con);

//-------------------------------------------------------------------
$shopconfig["hometitle"]=$lang['company'].'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]=$lang['company'].','.$shopconfig["homedes"];
$shopconfig["homekeyword"]=$lang['company'].','.$shopconfig["homekeyword"];

//====================================================================
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_profile.htm");
?>