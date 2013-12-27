<?php
/**
 * Powered by B2Bbuilder
 * Copyright http;//www.b2b-builder.com
 */
error_reporting(E_ERROR|E_WARNING|E_PARSE|E_USER_ERROR|E_USER_WARNING);//6143
header('Content-Type: text/html; charset=utf-8');
if(@function_exists('date_default_timezone_set'))
	@date_default_timezone_set('Asia/Shanghai');
	
$config['model']=1;//1 mall 2 shop
$config['version']='MallBuilder_v3.0';
$config['webroot']=substr(dirname(__FILE__), 0, -9);
ini_set('include_path',$config['webroot'].'/');

include_once($config['webroot']."/config/config.inc.php");
include_once($config['webroot']."/config/web_config.php"); 
include_once($config['webroot']."/config/table_config.php");
include_once($config['webroot']."/includes/convertip.php");
include_once($config['webroot']."/includes/function.php");
include_once($config['webroot']."/config/uc_config.php");
include_once($config['webroot']."/includes/db_class.php"); 

$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);

magic();//魔术调用
?>