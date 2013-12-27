<?php
/**
 * Powered by b2bbuilder
 * Copyright http://www.b2b-builder.com
 * Auther:brad zhang;
 */
//===================================================================
$sql="select * from ".SPE." where file_name='$_GET[name]'";//专题详情
$db->query($sql);
$de=$db->fetchRow();
if(empty($de['id']))
	msg('index.php');
$tpl->assign("detail",$de);
//--------------------------------------
session_start();
if(!isset($_SESSION["IFPAY"]))
{
	if(!empty($buid))
	{
		$sql="select ifpay from ".USER." WHERE userid='$buid'";
		$db->query($sql);
		$_SESSION["IFPAY"]=$db->fetchField('ifpay');
		if(empty($_SESSION["IFPAY"]))
			$_SESSION["IFPAY"]=2;
	}
	else
		$_SESSION["IFPAY"]=1;
}
if(!empty($gre[0])&&!in_array($_SESSION['IFPAY'],explode(",",$de['group'])))
{
	$tpl->assign("noright","true");
}

//---------------------------------------
useCahe("special/");
$flag='$_GET[name]';
$tpl->template_dir = $config["webroot"] . "/module/$_GET[m]/special_template/";
if(!$tpl->is_cached($de['template'],$flag))
{
	include_once("module/$_GET[m]/includes/includes_modules_class.php");//模组
	$md= new includes_modules();
	$layout=explode(",",$de['layout']);
	foreach($layout as $la)
	{
		$$la=isset($$la)?$$la:NULL;
		$sql="select * from ".MLAY." where layout='$la'  and type=1 and tid=$de[id] order by nums asc";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)//组合内容
		{
			if(!empty($v))
				$$la.= $md->$v['name']($v['filter']);//模组
		}
		$tpl->assign($la,$$la);//布局内容分发
	}
}
//====================================
$tpl->template_dir = $config["webroot"] . "/module/$_GET[m]/special_template/";
include("footer.php");
$tpl->display($de['template'],$flag);
?>