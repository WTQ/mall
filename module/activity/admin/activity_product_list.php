<?php
	include_once("$config[webroot]/includes/page_utf_class.php");


	if(!empty($_POST['chk']))
	{
		@$id=implode(",",$_POST['chk']);
		
		//批量删除
		if($_POST['submit']==$lang['del'] and $id)
		{ 
			$sql="delete from ".ACTIVITYPRODUCT." where product_id in ($id)";
			$db->query($sql);
			$sql="update ".PRO." set promotion_id='0' where id in ($id)";	
			$db->query($sql);
		}
		//审核通过
		if($_POST['submit']==$lang['tg'] and $id)
		{
			$sql="update ".ACTIVITYPRODUCT." set status='2' where product_id in ($id)";	
			$re=$db->query($sql);
		}
		//审核不通过
		if($_POST['submit']==$lang['btg'] and $id)
		{
			$sql="update ".ACTIVITYPRODUCT." set status='3' where product_id in ($id)";	
			$re=$db->query($sql);
			$sql="update ".PRO." set promotion_id='0' where id in ($id)";	
			$db->query($sql);
		}
		unset($_GET['s']);
		unset($_GET['m']);
		$getstr=implode('&',convert($_GET));
		msg("?m=activity&s=activity_product_list.php&$getstr");
	}
	
	if(!empty($_GET['t']) and is_numeric($_GET['t']))
	{
		$str=" and b.status = '$_GET[t]' ";
	}
	if(!empty($_GET['editid']) and is_numeric($_GET['editid']))
	{
		$str=" and b.activity_id = '$_GET[editid]' ";
	}
	
	//获取产品列表
	$sql="SELECT pname,product_id,displayorder,b.id,a.catid,a.price,a.pic,b.member_name,b.status FROM ".ACTIVITYPRODUCT." b left join ".PRO." a on a.id=b.product_id WHERE 1 $str order by b.displayorder,create_time desc";
	
	//分页
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$de['page'] = $page->prompt();
	
	$db->query($sql);
	$de['list']=$db->getRows();
	
	foreach($de['list'] as $key=>$val)
	{
		
		//获取产品类别
		$sql="select cat from ".PCAT." where catid = '".$val['catid']."'";
		$db->query($sql);
		$proCat = $db->getRows();
		$str="";
		foreach($proCat as $val)
		{
			$str.=$val['cat'];	
		}
		$de['list'][$key]['cat']=$str;
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/activity_product_list.htm");

?>