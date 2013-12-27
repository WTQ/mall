<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=================================================

//-----------------------审请新的链接---------------
if(isset($_POST["linkname"]))
{
	$sql="insert into ".LINK." 
	 (name,url,log)
	  values
	 ('$_POST[linkname]','$_POST[url]','$_POST[email]')";
	$re=$db->query($sql);
	if($re)
		header("Location:change_link.php?type=1");
	exit();
}
//-------------------------------------------------
$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
$db->query($sql);
$con_groups = $db->getRows();
$tpl->assign("con_groups",$con_groups);

$sql="select * from ".WEBCON." where con_statu=1 and lang='$config[language]' order by con_no asc";
$db->query($sql);
$all_web = $db->getRows();
$tpl->assign("all_web",$all_web);
//--------------------------------------------------


$sql="select url,name,log from ".LINK." where statu>=1 order by log desc,orderid asc";
$db->query($sql);
$link=$db->getRows();
$tpl->assign("link",$link);
include_once("footer.php");
//====================================================
$de['con_title']=$lang['flink'];
$de['con_desc']=$tpl -> fetch("change_link.htm");
$tpl->assign("de",$de);
$tpl->display("aboutus.htm");
?>