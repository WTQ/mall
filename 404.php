<?php
/**
 * powered by b2bbuilder
 * Copyright http://www.b2b-builder.com
 * Auther:brad zhang;
 * Des:abouts us
 */
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//================================================
if($buid&&(substr($_SERVER['REQUEST_URI'],0,9)=='/main.php'||$_SERVER['REQUEST_URI']=='/login.php'||$_SERVER['REQUEST_URI']=="/$config[regname]"))
{
	$sql="update ".ALLUSER." set statu='1' where userid='$buid'";
	$db->query($sql);
	
	include_once("$config[webroot]/config/reg_config.php");
	$config = array_merge($config,$reg_config);
	bsetcookie("USERID",NULL,time(),"/");
	setcookie("USER",NULL,time(),"/");
	unset($_SESSION["IFPAY"]);unset($_SESSION['UTYPE']);
}
//================================================
include_once("footer.php");
$tpl->display("404.htm");
?>