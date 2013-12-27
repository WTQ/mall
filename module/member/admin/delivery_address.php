<?php
	
	//删除
	if($_GET['delid'])
	{
		$sql="delete from ".DELIVERYADDR."  where id='$_GET[delid]'";
		$db->query($sql);
		msg("?m=member&s=delivery_address.php");
	}
	//批量删除
	if($_POST['act']=='op')
	{
		if(is_array($_POST['chk']))
		{
			$id=implode(",",$_POST['chk']);
			$sql="delete from ".DELIVERYADDR." where id in ($id)";
			$db->query($sql);
		}
		msg("?m=member&s=delivery_address.php");
	}
	
	$sql="select a.*,b.user from ".DELIVERYADDR." a left join ".MEMBER." b on a.userid=b.userid ";
	//================================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$de['page'] = $page->prompt();
	//=================================
	$db->query($sql);
	$de['list']=$db->getRows();

	
	$tpl->assign("de",$de);
	$tpl->display("admin/delivery_address.htm");
?>