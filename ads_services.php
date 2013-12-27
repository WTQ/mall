<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
$flag=md5('ads_services.htm');
useCahe();
if(!$tpl->is_cached("ads_services.htm",$flag))
{
	$db->query("select * from ".ADVS." order by ID asc");
	$re=$db->getRows();
	$tpl->assign("ad",$re);
	include_once("footer.php");
}
$tpl->display("ads_services.htm",$flag);
