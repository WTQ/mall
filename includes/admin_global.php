<?php
include_once($config["webroot"]."/lib/smarty/Smarty.class.php");
//==================================================
if(!empty($config['enable_gzip']) && function_exists('ob_gzhandler'))
	ob_start('ob_gzhandler');
//---------------------------------------
$tpl =  new Smarty();
$tpl -> left_delimiter  = "<{";
$tpl -> right_delimiter = "}>";
$tpl -> template_dir    = $config["webroot"] . "/templates/".$config['temp']."/";
$tpl -> compile_dir     = $config["webroot"] . "/templates_c/".$config['temp']."/";
//=================================================
$b2bbuilder_auth=bgetcookie("USERID");
$buid=$b2bbuilder_auth['0'];
$buser=$b2bbuilder_auth['1'];
$tpl -> assign("buid",$buid);
?>