<?php

	$parent_id=$_GET['id']?$_GET['id']:"0";
	

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			//添加
			if($_POST["act"]=='save')
			{
				foreach(explode("\r\n",$_POST['catname']) as $catv)
				{
					if(!empty($catv))
					{
						$sql="insert into ".BRANDCAT." (`catname`,`parent_id`) values ('$catv','$_POST[pid]')";
						$db->query($sql);
					}
				}
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".BRANDCAT." set catname='$_POST[catname]',parent_id='$_POST[pid]' where id='$_POST[id]'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=brand&s=brand_cat.php&$getstr");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".BRANDCAT." where id='$_GET[editid]'";
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
			$sql="delete from ".BRANDCAT."  where id='$_GET[delid]'";
			$db->query($sql);
			$sql="delete from ".BRANDCAT."  where parent_id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=brand&s=brand_cat.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".BRANDCAT." where id in ($id)";
					$db->query($sql);
					$sql="delete from ".BRANDCAT." where parent_id in ($id)";
					$db->query($sql);
				}
				if($_POST['displayorder'])
				{
					foreach($_POST['displayorder'] as $key=>$list)
					{
						$db->query("update ".BRANDCAT." set displayorder='$list' where id='$key'");		
					}
				}
			}
			msg("?m=brand&s=brand_cat.php");
		}	
	}
	$sql="select * from ".BRANDCAT." where parent_id='$parent_id' order by displayorder ,id desc";
	$db->query($sql);
	$de=$db->getRows();
	if($parent_id=='0')
	{
		foreach($de as $k=>$v)
		{
			$sql="select count(*) as count from ".BRANDCAT." where parent_id='$v[id]'";
			$db->query($sql);
			$de[$k]['count']=$db->fetchField('count');	
		}
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/brand_cat.htm");
?>