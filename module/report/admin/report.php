<?php

	if($_GET['operation']=="edit")
	{
		
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			//处理
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".REPORT." set state='2' , handle_type='$_POST[type]', handle_message='$_POST[message]' , handle_datetime='".time()."' , handle_user='$_SESSION[ADMIN_USER]' where id = '$_POST[id]'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=report&s=report.php&$getstr");
		}
		//活动信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select a.* ,b.type_name from ".REPORT." a left join ".REPORTS." b on a.subject_id = b.id where a.id='$_GET[editid]' order by id desc";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	else
	{
		//删除活动
		if($_GET['delid'])
		{
			$sql="delete from ".REPORT."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=report&s=report.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".REPORT." where id in ($id)";
				$db->query($sql);
			}
			msg("?m=report&s=report.php");
		}	
		$sql="select a.* ,b.type_name from ".REPORT." a left join ".REPORTS." b on a.subject_id = b.id order by id desc";	  
		//====================
		include_once("../includes/page_utf_class.php");
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
		$de['page']=$page->prompt();
		$tpl->assign("editimg",$editimg);
		$tpl->assign("delimg",$delimg);
	}

	$tpl->assign("de",$de);
	$tpl->display("admin/report.htm");
?>