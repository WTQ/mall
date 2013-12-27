<?php
include_once("includes/page_utf_class.php");
include_once("$config[webroot]/module/activity/includes/plugin_activity_class.php");
//================================================================
$activity=new activity();
$re=$activity->get_activity_list();

$tpl->assign("re",$re);
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_activity.htm");
?>