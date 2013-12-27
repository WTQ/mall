<?php
	include_once("../includes/page_utf_class.php");
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();

	$get=$_GET;
	unset($get['editid']);
	unset($get['s']);
	unset($get['m']);
	unset($get['grade']);
	unset($get['catid']);
	unset($get['operation']);
	$getstr=implode('&',convert($get));
	$tpl->assign("getstr",$getstr);
	
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			if($_POST['submit']==$lang['pass1'] and $id)
			{
				$sql="update ".SHOP." set shop_statu='1' where userid in ($id)";	
				$re=$db->query($sql);
			}
			msg("?m=shop&s=shop_application.php",'');
		}
	}	
	$sql=" and shop_statu=0";
	$tpl->assign("de",$shop->GetShopList($sql));
	
	//获取店铺类型 
	$tpl->assign("grade",$shop->GetShopGradeList());
		
	$tpl->assign("config",$config);
	$tpl->display("admin/shop_application.htm");
?>