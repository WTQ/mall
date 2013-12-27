<?php

	include_once("$config[webroot]/includes/page_utf_class.php");
	
	if($_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".SHOPLINK." set name='$_POST[name]',url='$_POST[url]', `desc`='$_POST[desc]',status='$_POST[status]' where id='$_POST[id]'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			msg("?m=shop&s=shop_link.php");
		}
		
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select a.*,b.user as member_name,b.company as shop_name from ".SHOPLINK." a left join ".SHOP." b on shop_id=userid where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
	}
	else
	{	
		//删除
		if($_GET['delid'])
		{
			$db->query("delete from ".SHOPLINK." where id='$_GET[delid]'");
			msg("?m=shop&s=shop_link.php");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".SHOPLINK." where id in ($id)";
					$db->query($sql);
				}
			}
			else
			{
				if(is_array($_POST['chk']))
				{
					foreach($_POST['chk'] as $val)
					{
						if($_POST['submit']==$lang['btn_open'])
						{
							$db->query("update ".SHOPLINK." set status='1' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_close'])
						{
							$db->query("update ".SHOPLINK." set status='0' where id='$val'");
						}
					}
				}
			}
			msg("?m=shop&s=shop_link.php");
		}
		
		$sql="select a.*,b.user as member_name,b.company as shop_name from ".SHOPLINK." a left join ".SHOP." b on shop_id=userid order by id desc";
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
	$tpl->display("admin/shop_link.htm");			

?>
