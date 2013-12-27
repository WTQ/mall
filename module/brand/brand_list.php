<?php
include("module/brand/includes/plugin_brand_class.php");
//=====================

	$mybrand=new brand();
	
	$sql="select * from ".BRANDCAT." where parent_id ='0'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("cat",$re);
	
	$id=(is_numeric($_GET['id']) and !empty($_GET['id']))?$_GET['id']:0;
	$sql="select * from ".BRANDCAT." where parent_id ='$id'";
	$db->query($sql);
	$de=$db->getRows();
	if(empty($_GET['etgid']))
	{
		foreach($de as $key=>$val)	
		{
			$sql="select id,name,logo from ".BRAND." where catid=$val[id] order by id desc limit 0,2";
			$db->query($sql);
			$re=$db->getRows();
			foreach($re as $key=>$val)
			{
				$sql="select id,pname,userid,price,pic from ".PRO." where brand='".$val['name']."' and statu>0 limit 0,4";
				$db->query($sql);
				$re[$key]['pro']=$db->getRows();	
			}
			$de[$key]['value']=$re;
		}
	}
	else
	{
		$re=$mybrand->brand_list();
		foreach($re['lists'] as $key=>$val)
		{
			$sql="select id,pname,userid,price,pic from ".PRO." where brand='".$val['name']."' and statu>0 limit 0,4";
			$db->query($sql);
			$re['lists'][$key]['pro']=$db->getRows();	
		}
		$tpl->assign("brand",$re);
	}
	
	$tpl->assign("bcat",$de);
	
	
//===========================
$tpl->assign("current","brand");
include("footer.php");
$out=tplfetch("brand_list.htm");
?>