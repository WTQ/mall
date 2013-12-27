<?php
include("module/brand/includes/plugin_brand_class.php");
//=====================

	$mybrand=new brand();
	for($i=65;$i<=90;$i++){          
	  $AZ[]=chr($i);
	}
	$tpl->assign("AZ",$AZ);
	
	$sql="select * from ".BRANDCAT." where parent_id ='0'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("cat",$re);
	if(!empty($_GET['id']))
	{
		$sql="select id from ".BRANDCAT." where parent_id='$_GET[id]'";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key=>$val)
		{
			$str.=$val['id'].',';
		}
		$str=$str.$_GET['id'];
		$str=" and catid in ($str) ";
	}
	
	if(empty($_GET['firstLetter']))
	{
		foreach($AZ as $key=>$val)
		{
			$sql="select id,name from ".BRAND." where char_index ='$val' limit 0,12";
			$db->query($sql);
			$de[$key]=$db->getRows();
		}
		$tpl->assign("brand",$de);
	}
	else
	{
		$sql="select id,name from ".BRAND." where char_index ='$_GET[firstLetter]' $str";
		$db->query($sql);
		$de=$db->getRows();
		$tpl->assign("brand",$de);
	}
//===========================
$tpl->assign("current","brand");
include("footer.php");
$out=tplfetch("brand_azlist.htm");
?>