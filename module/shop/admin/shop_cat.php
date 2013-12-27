<?php
	//删除
	if($_GET['delid'])
	{
		$sql="delete from ".SHOPCAT."  where id='$_GET[delid]'";
		$db->query($sql);
		$sql="delete from ".SHOPCAT."  where parent_id='$_GET[delid]'";
		$db->query($sql);
		msg("?m=shop&s=shop_cat.php");
	}
	if($_POST['act']=='op')
	{
		if($_POST['name'])
		{
			foreach($_POST['name'] as $key=>$list)
			{
				if(!empty($list))
				{
					$displayorder=$_POST['displayorder'][$key];
					$displayorder=$displayorder?$displayorder*1:"255";
					$db->query("update ".SHOPCAT." set name='$list',displayorder='$displayorder' where id='$key'");		
				}
			}
		}
		if(!empty($_POST['newname1']))
		{
			$inserts=array();
			foreach($_POST['newname1'] as $key=>$list)
			{
				foreach($list as $k=>$l)
				{
					if(!empty($l))
					{
						$displayorder=$_POST['neworder'][$key][$k];
						$displayorder=$displayorder?$displayorder*1:"255";
						$inserts[]="('$l','$key','$displayorder')";	
					}
				}
			}
			if(!empty($inserts))
			{
				$sql="insert into ".SHOPCAT." (`name`,`parent_id`,`displayorder`) values ".implode(",",$inserts);
				$db->query($sql);
			}
		}
		if(!empty($_POST['newname']))
		{
			$inserts=array();
			foreach($_POST['newname'] as $key=>$list)
			{
				if(!empty($list))
				{
					$displayorder=$_POST['newdisplayorder'][$key];
					$displayorder=$displayorder?$displayorder*1:"255";
					$inserts[]="('$list','0','$displayorder')";	
				}
			}
			if(!empty($inserts))
			{
				$sql="insert into ".SHOPCAT." (`name`,`parent_id`,`displayorder`) values ".implode(",",$inserts);
				$db->query($sql);
			}
		}
		msg("?m=shop&s=shop_cat.php");
	}
	
	$sql="select * from ".SHOPCAT." where parent_id='$parent_id' order by displayorder ,id ";
	$db->query($sql);
	$de=$db->getRows();
	foreach($de as $k=>$v)
	{
		$sql="select * from ".SHOPCAT." where parent_id='$v[id]'";
		$db->query($sql);
		$de[$k]['scat']=$db->getRows();	
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/shop_cat.htm");
?>