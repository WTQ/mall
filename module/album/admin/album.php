<?php

	if($_GET['id'])
	{
		
		unset($_GET['s']);
		unset($_GET['m']);
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".ALBUM." where id='$_GET[delid]'";
			$db->query($sql);
			$id=$_GET['delid'];
			unset($_GET['delid']);
			$getstr=implode('&',convert($_GET));
			msg("?m=album&s=album.php&$getstr");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".ALBUM." where id in ($id)";
				$db->query($sql);
				$getstr=implode('&',convert($_GET));
				msg("?m=album&s=album.php&$getstr");
			}
		}	
		
		$sql="SELECT a.*,b.company,b.user FROM ".ALBUM." a,".USER." b  WHERE a.userid=b.userid and a.catid='$_GET[id]'";
		//=============================
		include_once("../includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		$de['page']= $page->prompt();
		//==================================
		$db->query($sql);
		$de['list']=$db->getRows();
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".CUSTOM_CAT." where id='$_GET[delid]'";
			$db->query($sql);
			
			$sql="SELECT id FROM ".ALBUM." WHERE catid='$_GET[delid]'";
			$db->query($sql);
			$re=$db->getRows();
			foreach($re as $list)
			{
				$id=$list['id'];
				$sql="delete from ".ALBUM." where id='$id'";
				$db->query($sql);
			}
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			msg("?m=album&s=album.php&$getstr");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".CUSTOM_CAT." where id in ($id)";
				$db->query($sql);
				foreach($_POST['chk'] as $list)
				{
					$sql="SELECT id FROM ".ALBUM." WHERE catid='$list'";
					$db->query($sql);
					$re=$db->getRows();
					foreach($re as $val)
					{
						$id=$val['id'];
						$sql="delete from ".ALBUM." where id='$id'";
						$db->query($sql);
					}
				}
				msg("?m=album&s=album.php");
			}
		}
		$sql="SELECT a.id,a.tj,a.name,a.des,b.company,a.pic FROM ".CUSTOM_CAT." a,".USER." b  WHERE a.userid=b.userid and a.type=6 ORDER BY a.ID DESC";
		//=============================
		include_once("../includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		$de['page']= $page->prompt();
		//==================================
		$db->query($sql);
		$de['list']=$db->getRows();
	}
	
	$tpl->assign("delimg",$delimg);
	$tpl->assign("setimg",$setimg);

	$tpl->assign("de",$de);
	$tpl->display("admin/album.htm");

?>
