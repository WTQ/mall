<?php
	
	include_once("../includes/page_utf_class.php");
	
	if($_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			
			//修改
			if($_POST["act"]=='shipping' and is_numeric($_POST['id']))
			{
				$sql="update ".TGORDER." set shipping_name='$_POST[shipping_name]',shipping_address='$_POST[t] $_POST[shipping_address]',shipping_tel='$_POST[shipping_tel]',shipping_company='$_POST[shipping_company]',shipping_code='$_POST[shipping_code]',status='40',shipping_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			if($_POST["act"]=='cancel' and is_numeric($_POST['id']))
			{
				$admin_remark=$_POST['other_reason']?$_POST['admin_remark']." ".$_POST['other_reason']:$_POST['admin_remark'];
				$sql="update ".TGORDER." set admin_remark='$admin_remark',status='10',finished_time='$time' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg_order.php$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".TGORDER." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
			
			$sql="select * from ".FASTMAIL."  order by id";
			$db->query($sql);
			$re=$db->getRows();
			$tpl->assign("re",$re);
			
			$tpl->assign("district",GetDistrict());
			$tpl->assign("config",$config);
		}
	}
	else
	{
		$str=NULL;
		if(!empty($_GET["type"]) and is_numeric($_GET['type']))
		{
			$str=" and status = '$_GET[type]' ";
		}
		$sql="select * from ".TGORDER." where 1 $str order by id desc ";
		//=============================
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$pages = $page->prompt();
		//=====================
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
	}
	
	
	$statu_list =array('10'=>'已取消','20'=>'未支付','30'=>'未发货','40'=>'已发货','50'=>'已完成');

	$tpl->assign("de",$de);
	$tpl->assign("statu_list",$statu_list);
	$tpl->display("admin/tg_order.htm");

?>