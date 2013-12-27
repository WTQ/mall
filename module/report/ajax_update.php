<?php
include_once("../../includes/global.php");
include_once("../../includes/smarty_config.php");
//===============================================

include_once($config['webroot']."/module/report/includes/plugin_report_class.php");
$report=new report();
if(isset($_POST['type_id']))
{
	$subject=$report->get_report_subject($_POST['type_id']);
	$str="{";
	foreach($subject as $key=>$val)
	{
		$id=$val["id"];
		$content=$val["content"];
		if($num==0)
			$str.="'$i':{'0':'$id,$content','1':'$content'}";
		else
			$str.="'$i':{'0':'$id,$content','1':'$content'},";
	}
	//------------------------------------------------------------
	$str.="};";
	echo $str;die;
}

		
		
	

?>