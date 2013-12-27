<?php

	include_once("$config[webroot]/includes/page_utf_class.php");
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();
	//删除
	if($_GET['delid'])
	{
		$sql="delete from ".SHOPEARNEST." where shop_id='$_GET[delid]'";
		$db->query($sql);
		$db->query("update ".SHOP." set earnest=0 where userid='$_GET[delid]'");
		unset($_GET['delid']);
		$getstr=implode('&',convert($_GET));
		msg("?m=shop&s=shop_earnest.php&$getstr");
	}
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			$sql="delete from ".SHOPEARNEST." where shop_id in ($id)";
			$db->query($sql);
			$db->query("update ".SHOP." set earnest=0 where userid in ($id)");	
		}
		msg("?m=shop&s=shop_earnest.php");
	}	
	//获取
	$sql="select money,admin,a.create_time as time ,b.* from ".SHOPEARNEST." a left join ".SHOP." b on a.shop_id=b.userid order by a.id";
	$page = new Page;
	$page->listRows=20;
	//分页
	if (!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$db->query($sql);
	$de['list']=$db->getRows();
	foreach($de['list'] as $key=>$v)
	{
		//获取当前店铺类型
		$sql="select name from ".SHOPGRADE." where id='$v[grade]'";
		$db->query($sql);
		$grade=$db->fetchField('name');
		$de['list'][$key]['grade']=$grade;
		$de['list'][$key]['cat']=$shop->GetShopCatName($v['catid']);
	}
	$de['page']=$page->prompt();
	
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("admin/shop_earnest.htm");

?>