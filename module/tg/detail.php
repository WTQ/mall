<?php
/**
 * Copyright :http://www.b2b-buildr.com
 * Powered by :B2Bbuilder
 */

include_once("includes/page_utf_class.php");
//==================================================================
if(!empty($_GET['ajax']))
{
	//ajax商品加一
	$id=$_GET["id"];
	$sql="update ".TG." set hits=hits+1 where id='$id'";
	$db->query($sql);
	die;
}
$id=$_GET['id']*1;

//-----------------------------推荐商品
$sql="select * from ".TG." where status=2 and id <> $id order by displayorder limit 0,5";
$db->query($sql);
$re=$db->getRows();		
$tpl->assign("tj",$re);
	
//----------------------------详情信息

$sql="select * from ".TG." where id='$id'";
$db->query($sql);
$re=$db->fetchRow();
if($re['id'])
{
	$sql="update ".TG." set hits=hits+1 where id='$id'";
	$db->query($sql); 
	$tpl->assign("detail",$re);
}
else
{
	msg("$config[weburl]/?m=tg",'产品不存在');
}
//-------------------------SEO
$sql="select catname from ".TGCAT." where id='$re[catid]'";
$db->query($sql);
$cat=$db->fetchField('catname');
	
$ar1=array('[catname]','[title]');
$ar2=array($cat,$re['downname']);
$config['title']=str_replace($ar1,$ar2,$config['title3']);
$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
$config['description']=str_replace($ar1,$ar2,$config['description3']);
//====================================================
include_once("footer.php");
$tpl->assign("current","tg");
$out=tplfetch("tg_detail.htm");
?>