<?php
include_once("../includes/page_utf_class.php");
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//====================================

	
	$get=$_GET;
	unset($get['editid']);
	unset($get['s']);
	unset($get['m']);
	unset($get['grade']);
	unset($get['catid']);
	unset($get['operation']);
	$getstr=implode('&',convert($get));
	$tpl->assign("getstr",$getstr);
	
	
	if($_POST['act']=='shopkeeper')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			
			if($_POST['submit']==$lang['pass1'] and $id)
			{
				$sql="update ".SHOP." set shopkeeper_auth='1' where userid in ($id)";	
				$re=$db->query($sql);
			}
			if($_POST['submit']==$lang['npass'] and $id)
			{
				$sql="update ".SHOP." set shopkeeper_auth='-1',shopkeeper_auth_pic='' where userid in ($id)";	
				$re=$db->query($sql);
			}
			msg("?m=shop&s=shop_certification.php&operation=shopkeeper",'');
		}
	}	
	if($_POST['act']=='shop')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			if($_POST['submit']==$lang['pass1'] and $id)
			{
				$sql="update ".SHOP." set shop_auth='1' where userid in ($id)";	
				$re=$db->query($sql);
			}
			if($_POST['submit']==$lang['npass'] and $id)
			{
				$sql="update ".SHOP." set shop_auth='-1',shop_auth_pic='' where userid in ($id)";	
				$re=$db->query($sql);
			}
			msg("?m=shop&s=shop_certification.php",'');
		}
	}
	
	if($_GET['operation'])
		$sql=" and shopkeeper_auth!=1 and (shopkeeper_auth_pic is not NULL and shopkeeper_auth_pic  != '') ";
	else
		$sql=" and shop_auth!=1 and (shop_auth_pic is not NULL and shop_auth_pic != '' ) ";
	
	$tpl->assign("de",$shop->GetShopList($sql));
	
	//获取店铺类型
	$tpl->assign("grade",$shop->GetShopGradeList());
	
	$tpl->assign("config",$config);
	$tpl->display("admin/shop_certification.htm");
?>