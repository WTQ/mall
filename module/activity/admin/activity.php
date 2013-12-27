<?php

	include_once("$config[webroot]/includes/page_utf_class.php");
	include_once($config['webroot']."/module/activity/includes/plugin_activity_class.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['s']);
			unset($_GET['m']);
			unset($_GET['operation']);
			//添加活动
			if($_POST["act"]=='save')
			{
				$activity=new activity();
				$activity->add_activity();
			}
			//修改活动
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$activity=new activity();
				$activity->edit_activity();
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=activity&s=activity.php&$getstr");
		}
		//活动信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".ACTIVITY." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	else
	{
		//删除活动
		if($_GET['delid'])
		{
			$sql="delete from ".ACTIVITY."  where id='$_GET[delid]'";
			$db->query($sql);
			$sql="delete from ".ACTIVITYPRODUCT."  where activity_id='$_GET[delid]'";
			$db->query($sql);
			$db->query("update ".PRO." set promotion_id=0 where promotion_id='$_GET[delid]'");
			unset($_GET['delid']);
			$getstr=implode('&',convert($_GET));
			msg("?m=activity&s=activity.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".ACTIVITY." where id in ($id)";
				$db->query($sql);
				$sql="delete from ".ACTIVITYPRODUCT." where activity_id in ($id)";
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
					$db->query("update ".ACTIVITY." set displayorder='$list' where id='$key'");		
				}
			}
			msg("?m=activity&s=activity.php");
		}	
		//获取活动
		$sql="select * from ".ACTIVITY."  order by displayorder";
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
		if($de['list'])
		{
			foreach($de['list'] as $key=>$v)
			{
				//获取活动下产品总数
				$sql="select * from ".ACTIVITYPRODUCT." where activity_id='$v[id]' ";
				$db->query($sql);
				$de['list'][$key]['num']=$db->num_rows();
			}
		}
		$de['page']=$page->prompt();
	}
	$tpl->assign("de",$de);
	$tpl->assign("config",$config);
	$tpl->display("admin/activity.htm");

?>