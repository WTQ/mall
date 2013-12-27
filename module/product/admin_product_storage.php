<?php
	include_once("includes/page_utf_class.php");
	include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");
	$pro=new pro();
	//============================================================================
	if(!empty($deid))
	{
		$pro->del_pro($deid);
		$admin->msg("main.php?m=product&s=admin_product_storage&statu=$_GET[statu]");
	}
	elseif(isset($_GET['update']))
	{
		$pro->update_pro($_GET['update']);
	}
	if(!empty($_GET['cstatu'])&&!empty($_GET['ppid']))
	{
		$pro->set_pro_statu($_GET['ppid'],$_GET['cstatu']);
		$admin->msg("main.php?m=product&s=admin_product_storage&statu=$_GET[statu]");
	}

	$_GET['statu']=isset($_GET['statu'])?$_GET['statu']:"-2";
	$tpl->assign("re",$pro->pro_list($_GET['statu']));
	
	
	if($_GET['pid'])
	{
		$pid=explode(',',$_GET['pid']);
		foreach($pid as $val)
		{
			$pro->del_pro($val);
		}
		die;
	}
	//==================================
	$nocheck=true;
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_product_storage.htm");
?>