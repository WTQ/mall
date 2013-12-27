<?php
	
	include_once("module/shop/includes/plugin_shop_class.php");
	$shop=new shop();
	
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			//添加
			if($_POST["act"]=='save')
			{
				$shop->AddShopGrade();;
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$shop->EditShopGrade($_POST['id']);
			}
			msg("?m=shop&s=shop_grade.php");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$de=$shop->GetShopGrade($_GET['editid']);
		}
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".SHOPGRADE."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("?m=shop&s=shop_grade.php");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".SHOPGRADE." where id in ($id)";
				$db->query($sql);
			}
			msg("?m=shop&s=shop_grade.php");
		}
		//获取信息
		$de['list']=$shop->GetShopGradeList();
		if(empty($de['list']))
		{
			$sql="INSERT INTO ".SHOPGRADE." (id, name, fee, `desc`, create_time, status) VALUES
			(1, '系统默认', 10, '系统默认', 1365485867, 1),
			(2, '铜牌店铺', 20, '铜牌店铺', 1365485879, 1),
			(3, '金牌店铺', 30, '金牌店铺', 1365485898, 1),
			(4, '白金店铺', 40, '白金店铺', 1365485914, 1);";
			$db->query($sql);
			admin_msg("module.php?m=shop&s=shop_grade.php","数据导入成功");
		}
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/shop_grade.htm");
?>
