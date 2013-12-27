<?php
//------------单项物流价格----------------
function get_log_price($lgid,$area)
{
	global $db;
	if(strlen($area)>6)
		$city=substr($area,6,strlen($area)-6);
	else
		$city=$area;
	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='mail'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='mail'";
		
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str="平邮:$re[default_price]元 ";
	
	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='ems'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='ems'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str.="EMS:$re[default_price]元 ";
	
	$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='express'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['id']))
	{	//没有为城市定价
		$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='express'";
		$db->query($sql);
		$re=$db->fetchRow();
	}
	$str.="快递:$re[default_price]元 ";
	return $str;
	
}
//------------产品详情--------------------
include_once($config['webroot']."/module/product/includes/plugin_pro_class.php");
$id=$_GET["id"]*1;
$prodetail=new pro();
$prode=$prodetail->detail($id);
//-----------------------------------------
$sql="select isbuy,ext_table from ".PCAT." where catid='$prode[catid]'";
$db->query($sql);
$current_cat=$db->fetchRow();
if($current_cat['isbuy']==1)
	$prode['isbuy']=1;
else
	$prode['isbuy']=0;
//-----------------------------------扩展字段
include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
$addfield = new AddField('product');
$prode['extfiled']=$addfield->addfieldinput($id,$current_cat['ext_table'],true);
//-----------------------------------用户区获取
$prode['user_ip']=convertip(getip());
if($prode['user_ip']=='- LAN')
	$prode['user_ip']='';
$prode['freight_count']=get_log_price($prode['freight'],$prode['user_ip']);//跟据所在地自动算出的运费
//----------------------------------------
if(!empty($prode['userid']))
{
	$_GET['uid']=$prode['userid'];
	$_GET['action']='product_detail';
	include($config['webroot'].'/shop.php');
}
else
{
	//------------导航，keyword，description---
	$parcat=substr($prode['catid'],0,4);
	$sql="select catid,cat,isbuy,ext_table from ".PCAT." where catid='$parcat'";
	$db->query($sql);
	$pcats=$db->fetchRow();
	$tpl->assign("pcat",$pcats);
	$ext_table=$pcats['ext_table'];
	//------------------------------------------
	if(!empty($pcats['cat']))
	{
		$ks.=$pcats['cat'];
		$guide.="<a href='$config[weburl]/?m=product&s=list&id=$parcat'>".$pcats['cat']."</a>&raquo;";
	}
	if(strlen($prode['catid'])>=6)
	{	
		$parcat=substr($prode['catid'],0,6);
		$sql="select catid,cat,isbuy,ext_table from ".PCAT." where catid='$parcat'";
		$db->query($sql);
		$pcats=$db->fetchRow();
		$ext_table=$pcats['ext_table'];
		if(!empty($pcats['cat']))
		{
			$ks.=','.$pcats['cat'];
			$guide.="<a href='$config[weburl]/?m=product&s=list&id=$parcat'>".$pcats['cat']."</a>&raquo;";
		}
	}
	if(strlen($prode['catid'])>=8)
	{	
		$parcat=substr($prode['catid'],0,8);
		$sql="select catid,cat,isbuy,ext_table from ".PCAT." where catid='$parcat'";
		$db->query($sql);
		$pcats=$db->fetchRow();
		$ext_table=$pcats['ext_table'];
		if(!empty($pcats['cat']))
		{
			$ks.=','.$pcats['cat'];
			$guide.="<a href='$config[weburl]/?m=product&s=list&id=$parcat'>".$pcats['cat']."</a>&raquo;";
		}
	}
	if(strlen($prode['catid'])>=10)
	{	
		$parcat=substr($prode['catid'],0,10);
		$sql="select catid,cat,isbuy,ext_table from ".PCAT." where catid='$parcat'";
		$db->query($sql);
		$pcats=$db->fetchRow();
		$ext_table=$pcats['ext_table'];
		if(!empty($pcats['cat']))
		{
			$ks.=','.$pcats['cat'];
			$guide.="<a href='$config[weburl]/?m=product&s=list&id=$parcat'>".$pcats['cat']."</a>&raquo;";
		}
	}
	
	//-----------------------------------------------
	$tpl->assign("prod",$prode);
	$tpl->assign("guide",$guide.$prode['pname']);
	
	$ar1=array('[catname]','[title]','[keyword]','[brand]');
	$ar2=array($pcats['cat'],$prode['pname'],$prode['keywords'],$prode['brand']);
	$config['title']=str_replace($ar1,$ar2,$config['title3']);
	$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
	$config['description']=str_replace($ar1,$ar2,$config['description3']);
	//======================================
	$tpl->assign("current","product");
	include_once("footer.php");
	$out=tplfetch("product_detail.htm",$flag);
}
?>