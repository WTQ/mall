<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=========================================
if(!$tpl->is_cached("member_services.htm"))
{
	$sql="select * from ".USERGROUP." order by group_id asc";
	$db->query($sql);
	$group=$db->getRows();
	
	$tpl->assign("group",$group);
	include("footer.php");
}
$tpl->display("member_services.htm");
?>