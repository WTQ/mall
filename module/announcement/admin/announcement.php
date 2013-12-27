<?php
	
	include_once("$config[webroot]/includes/page_utf_class.php");
	
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=strtotime($_POST['create_time']);
			
			//添加公告
			if($_POST["act"]=='save')
			{
				$sql="insert into  ".ANNOUNCEMENT." (title,content,url,create_time,status,displayorder) values ('$_POST[title]','$_POST[content]','$_POST[url]','$time','$_POST[status]','255')";
				$db->query($sql);
			}
			
			//修改公告
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".ANNOUNCEMENT." set title='$_POST[title]',content='$_POST[content]',url='$_POST[url]',create_time='$time',status='$_POST[status]' where id='$_POST[id]'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=announcement&s=announcement.php&$getstr");
		}
		//公告信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".ANNOUNCEMENT." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		$tpl->assign("time",time());
	}
	else
	{	
		//删除公告
		if($_GET['delid'])
		{
			$db->query("delete from ".ANNOUNCEMENT." where id='$_GET[delid]'");
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=announcement&s=announcement.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".ANNOUNCEMENT." where id in ($id)";
				$db->query($sql);
				foreach($_POST['chk'] as $list)
				{
					$db->query("update ".PRO." set promotion_id=0 where promotion_id='$list'");	
				}
			}
			if($_POST['displayorder'])
			{
				foreach($_POST['displayorder'] as $key=>$list)
				{
					$db->query("update ".ANNOUNCEMENT." set displayorder='$list' where id='$key'");		
				}
			}
			msg("?m=announcement&s=announcement.php");
		}
		
		$sql="select * from ".ANNOUNCEMENT." order by displayorder,id desc";
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
	}
	
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("admin/announcement.htm");
?>