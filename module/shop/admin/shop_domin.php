<?php
	
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			//添加
			if($_POST["act"]=='save')
			{
				$tm=time();
			 
				$sql="insert into ".SHOPDOMIN." (`domin`,`shop_id`,`shop_name`,`member_name`,`create_time`) values ('".trim($_POST['domin'])."','$_POST[shop_id]','$_POST[shop_name]','$_POST[member_name]',".time().")";
				$db->query($sql);
				
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".SHOPDOMIN." set domin='".trim($_POST['domin'])."' where id='$_POST[id]'";
				$db->query($sql);
			}
			msg("?m=shop&s=shop_domin.php");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".SHOPDOMIN." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	elseif($_GET['operation']=="check")
	{
		echo "sadsad";die;
		$sql="select userid,company from ".SHOP." where user='".trim($_POST['member_name'])."'";
		$db->query($sql);	
		$shop=$db->fetchRow();
		if($shop['userid'])
		{
			$sql="select id from ".SHOPDOMIN." where shop_id='$shop[userid]'";
			$db->query($sql);	
			$id=$db->fetchField('id');
			if($id)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}	
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".SHOPDOMIN."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("?m=shop&s=shop_domin.php");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".SHOPDOMIN." where id in ($id)";
				$db->query($sql);
			}
			msg("?m=shop&s=shop_domin.php");
		}
		$sql="select * from ".SHOPDOMIN;
		//=============================
			include_once("../includes/page_utf_class.php");
			$page = new Page;
			$page->listRows=20;
			if (!$page->__get('totalRows')){
				$db->query($sql);
				$page->totalRows = $db->num_rows();
			}
			$sql .= "  limit ".$page->firstRow.",".$page->listRows;
			$de['page'] = $page->prompt();
		//=====================
		$db->query($sql);
		$de['list']=$db->getRows();
		
	}
	
	$tpl->assign("de",$de);
	$tpl->display("admin/shop_domin.htm");
?>
