<?php
include_once("$config[webroot]/module/report/includes/plugin_report_class.php");
$report=new report();
//===================================================================

$subject_type=$report->get_report_subject_type();
$tpl->assign("subject_type",$subject_type);

$subject=$report->get_report_subject($subject_type[0]['id']);
$tpl->assign("subject",$subject);

$product_info=$report->get_product_info($_GET['pid']);
$tpl->assign("product_info",$product_info);

if($_POST['submit']=='add')
{	
	$flag=$report->add_report();
	if($flag=='error')
	{
		$admin->msg('main.php?m=report&s=admin_add_report&type=add','参数错误','failure');     
	}
	else
	{
		$admin->msg('main.php?m=report&s=admin_myreport');
	}
}
//====================================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_add_report.htm");

?>