<?php

//=================================================================
useCahe();
$flag=md5($dpid.$dcid.$config["temp"]);
if(!$tpl->is_cached("tg_index.htm",$flag))
{
	$date=time();
	$_GET['catid']*=1;
	if($_GET['catid'])
		$catid=" and catid='$_GET[catid]'";
	$sql="select * from ".TG." where status>=1 $catid order by displayorder desc  ";
		
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("tg",$re);
	
	//-----------------------商品分类
	$sql="select * from ".TGCAT." where parent_id=0 order by displayorder desc";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("tgcat",$re);
}
//========================================================================
include_once("footer.php");
$tpl->assign("current","tg");
$out=tplfetch("tg_index.htm");

?>