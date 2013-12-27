<?php
/**
 * Copyright :http://www.b2b-buildr.com
 * Powered by :B2Bbuilder
 */
include_once("includes/news_function.php");
//========================================
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');

useCahe();
$flag=md5($dpid.$dcid.$config["temp"].$_COOKIE["langtw"]);
if(!$tpl->is_cached("news_index.htm",$flag))
{
	//----菜单---------
	$sql="SELECT * from ".NEWSCAT." WHERE ishome=1 and pid=0 order by nums asc";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("news_menu",$re);
	
	//----热门点击------
	$sql="SELECT nid,title FROM ".NEWSD." WHERE ispass=1 ORDER BY onclick DESC limit 0,8";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("hotnews",$re);
	
}
//==================================
include_once("footer.php");
$tpl->assign("current","news");
$out=tplfetch("news_index.htm",$flag);
?>