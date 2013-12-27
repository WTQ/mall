<?php
include_once("../includes/global.php");
$sctiptName=$_GET['s'];
include_once("auth.php");
@include_once("../module/".$_GET['m']."/lang/".$config['language'].'.php');
//===============================================
if(file_exists('../config/module_'.$_GET['m'].'_config.php'))
{
	@include('config/module_'.$_GET['m'].'_config.php');
	$mcon='module_'.$_GET['m'].'_config';
	@$config = array_merge($config,$$mcon);
}
//================================================
include_once($config["webroot"]."/lib/smarty/Smarty.class.php");
$tpl =  new Smarty();
$tpl -> left_delimiter  = "<{";
$tpl -> right_delimiter = "}>";
$tpl -> template_dir    = $config["webroot"] . "/module/$_GET[m]/templates/";
$tpl -> compile_dir     = $config["webroot"] . "/templates_c/".$config['temp']."/";
$tpl -> assign("lang",$lang);
//================================================
$tpl->assign("delimg",$delimg);
$tpl->assign("editimg",$editimg);
$tpl->assign("addimg",$addimg);
$tpl->assign("onimg",$onimg);
$tpl->assign("offimg",$offimg);
$tpl->assign("startimg",$startimg);
$tpl->assign("stopimg",$stopimg);
$tpl->assign("setimg",$setimg);
$tpl->assign("mailimg",$mailimg);
//====================================
include("../module/$_GET[m]/admin/$_GET[s]");
?>