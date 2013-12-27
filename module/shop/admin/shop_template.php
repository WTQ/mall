<?php

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			//添加
			if($_POST["act"]=='save')
			{
				$tm=time();
				$sql="insert into ".SHOPTEMP." (`name`,`style`,`temp_file`,`create_time`,`displayorder`,`status`)VALUES('$_POST[name]','$_POST[style]','$_POST[temp_file]','$tm','255','$_POST[status]')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".SHOPTEMP." set `name`='$_POST[name]',`style`='$_POST[style]',`temp_file`='$_POST[temp_file]',`status`='$_POST[status]' where id='$_POST[id]'";
				$db->query($sql);
			}
			msg("?m=shop&s=shop_template.php");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".SHOPTEMP." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		$templist=array();
		$handle = opendir($config['webroot']."/templates"); 
		while ($filename = readdir($handle))
		{ 
			if($filename!="."&&$filename!="..")
			{
			  if(is_dir($config['webroot']."/templates/".$filename)&&substr($filename,0,5)=="user_"&&$filename!='user_templates_default')
			  {
				$templist[]=$filename;
			  }
		   }
		}
		sort($templist);
		$tpl->assign("templist",$templist);
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".SHOPTEMP."  where id='$_GET[delid]'";
			$db->query($sql);
			msg("?m=shop&s=shop_template.php");
		}
		//批量删除
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				$id=implode(",",$_POST['chk']);
				$sql="delete from ".SHOPTEMP." where id in ($id)";
				$db->query($sql);
			}
			if($_POST['displayorder'])
			{
				foreach($_POST['displayorder'] as $key=>$list)
				{
					$db->query("update ".SHOPTEMP." set displayorder='$list' where id='$key'");		
				}
			}
			msg("?m=shop&s=shop_template.php");
		}
		$sql="select * from ".SHOPTEMP." where 1 order by displayorder asc";
		//================================
		include_once("../includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=10;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$de['page'] = $page->prompt();
		//=================================
		$db->query($sql);
		$de['list']=$db->getRows();
	}
	
	$tpl->assign("de",$de);
	$tpl->display("admin/shop_template.htm");
?>