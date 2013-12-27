<?php
include_once("$config[webroot]/module/report/includes/plugin_report_class.php");
$report=new report();
//===================================================================

$report=$report->get_myreportlist();
$tpl->assign("report",$report);	

//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_myreport.htm");
?>