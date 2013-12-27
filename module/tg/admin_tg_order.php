<?php
if($_POST['op']=="pickup" and !empty($_POST['id']))
{
	$time=time();
	$sql="update ".TGORDER." set status='50',finished_time='$time' where id='".$_POST['id']."'";
	$db->query($sql);
	echo $_POST['id'];die;
}
if($_GET['type']=='show' and is_numeric($_GET['id']))
{
	$sql="select * from ".TGORDER." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	$statu_list =array('10'=>'已取消','20'=>'未支付','30'=>'未发货','40'=>'已发货','50'=>'已完成');

	$tpl->assign("de",$de);
	$tpl->assign("statu_list",$statu_list);
}
else
{
	include_once("includes/page_utf_class.php");
	$sql="select * from ".TGORDER." where member_id='$buid' order by create_time desc";
	$page = new Page;
	$page->listRows=10;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",".$page->listRows;
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("tg",$re);
}
//========================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_tg_order.htm");
?>
