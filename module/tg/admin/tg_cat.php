<?php

	$parent_id=$_GET['id']?$_GET['id']:"0";
	

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$_POST['pid']=$_POST['pid']?$_POST['pid']:"0";
			//添加
			if($_POST["act"]=='save')
			{
				foreach(explode("\r\n",$_POST['catname']) as $catv)
				{
					if(!empty($catv))
					{
						$sql="insert into ".TGCAT." (`catname`,`parent_id`) values ('$catv','$_POST[pid]')";
						$db->query($sql);
					}
				}
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".TGCAT." set catname='$_POST[catname]',parent_id='$_POST[pid]' where id='$_POST[id]'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg_cat.php&$getstr");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".TGCAT." where id='$_GET[editid]'";
			$db->query($sql);
			$re=$db->fetchRow();
			$parent_id='0';
			$tpl->assign("re",$re);
		}
	}
	else
	{
		if($_GET['delid'])
		{
			$sql="delete from ".TGCAT."  where id='$_GET[delid]'";
			$db->query($sql);
			$sql="delete from ".TGCAT."  where parent_id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg_cat.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".TGCAT." where id in ($id)";
					$db->query($sql);
					$sql="delete from ".TGCAT." where parent_id in ($id)";
					$db->query($sql);
				}
				if($_POST['displayorder'])
				{
					foreach($_POST['displayorder'] as $key=>$list)
					{
						$db->query("update ".TGCAT." set displayorder='$list' where id='$key'");		
					}
				}
			}
			msg("?m=tg&s=tg_cat.php");
		}	
	}
	$sql="select * from ".TGCAT." where parent_id='$parent_id' order by displayorder ,id desc";
	$db->query($sql);
	$de=$db->getRows();
	
	$tpl->assign("de",$de);
	$tpl->display("admin/tg_cat.htm");
?>