<?php

	if($_POST['value'])
	{
		if($_POST['id'] and is_numeric($_POST['id']))
		{
			include_once("../../../includes/global.php");
			$sqls="";
			if($_POST['sid'])
			{
				$sqls=" and id!='$_POST[sid]'";
			}
			$sql = "select id from ".PROPERTYVALUE." where property_id='$_POST[id]' and field='".$_POST['value']."' $sqls";
			$db->query($sql);
			$id = $db->fetchField('id');
			if(!$id)
			{
				echo '1';die;
			}
			else
			{
				echo '0';die;
			}
		}
		else
		{
			echo '0';die;
		}
	}
	
	include_once("$config[webroot]/includes/page_utf_class.php");
	
	//=================================================
	function creat_table($id)
	{
		global $db,$config;
		$table_name=$config['table_pre']."defind_".$id;
		$csql="
		CREATE TABLE `".$table_name."` (
		  `id` int(11) NOT NULL auto_increment,
		  `info_id` int(11) unsigned default NULL,
		  `info_type` varchar(30) default NULL,
		  `color` varchar(255) default NULL,
		  `color_img` text default NULL,
		   PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
		";
		$db->query($csql);
		$sql="ALTER TABLE `$table_name` ADD INDEX ( `info_id`,`info_type` )";
		$db->query($sql);
		
		//----------------------初始默认字段
		$sql="INSERT INTO `".PROPERTYVALUE."` VALUES ( 0,'color', '颜色', '请选择颜色', 0, '','varchar', '255', 1, 5, '1|红色,2|蓝色', 0, $id, '', 1)";
		$db->query($sql);
		//----------------------
	}

	if($_GET['operation']=="list" and !empty($_GET['id']) and is_numeric($_GET['id']))
	{		
		if($_GET['delid'])
		{
			$sql="select * from ".PROPERTYVALUE." where id='$_GET[delid]'";
			$db->query($sql);
			$re=$db->fetchRow();
			if($re['field'])
			{
				$table_name=$config['table_pre']."defind_".$_GET['id'];
				$db->query("ALTER TABLE `$table_name` DROP `$re[field]`");
			}
			$sql="delete from ".PROPERTYVALUE." where id='$_GET[delid]'";
			$db->query($sql);
			msg("?m=product&s=property.php&operation=list&id=".$_GET['id']);
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					foreach($_POST["chk"] as $v)
					{
						$sql="select * from ".PROPERTYVALUE." where id='$v'";
						$db->query($sql);
						$re=$db->fetchRow();
						if($re['field'])
						{
							$table_name=$config['table_pre']."defind_".$_GET['id'];
							$db->query("ALTER TABLE `$table_name` DROP `$re[field]`");
						}
						$sql="delete from ".PROPERTYVALUE." where id=$v";
						$db->query($sql);
					}
				}							
				msg("?m=product&s=property.php&operation=list&id=".$_GET['id']);
			}
		}
		$sql="select * from ".PROPERTYVALUE." where property_id='$_GET[id]'";
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
		foreach($de['list'] as $key=>$val)
		{
			$str=explode(',',$val['item']);
			$arr=array();
			foreach($str as $v)
			{
				$str1=explode('|',$v);
				$arr[]=$str1[1];
			}
			$de['list'][$key]['items']=implode(",",$arr);
		}
		$de['page']=$page->prompt();
		$tpl->assign("type",$lang['displayType']);
		$tpl->assign("de",$de);
		$tpl->display("admin/property_value.htm");
	}
	elseif($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['s']);
			unset($_GET['m']);
			unset($_GET['operation']);
			//添加
			if($_POST["act"]=='save')
			{
				$id = $_GET['id']*1;
				$table_name=$config['table_pre']."defind_".$id;
				if($_POST['display_type']>2)
				{
					foreach($_POST['name'] as $key=>$val)
					{
						$displayorder=$_POST['displayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:rand(1,99999999);
						if($val and $displayorder)
						{
							$arr[]=$displayorder."|".$val;
						}
					}
					$item=implode(',',$arr);
				}
				$sql = "insert into ".PROPERTYVALUE." (`field`,field_name,field_desc,is_required,default_value,field_type,field_length,is_buy_item,display_type,item,is_search,property_id,module,statu) values ('$_POST[field]','$_POST[field_name]','$_POST[field_desc]','$_POST[is_required]','$_POST[default_value]','$_POST[field_type]','$_POST[field_length]','$_POST[is_buy_item]','$_POST[display_type]','$item','$_POST[is_search]','$id','','$_POST[statu]')";
				$db->query($sql);
				
				//表字段调整
				$sql1="ALTER TABLE `".$table_name."` ADD `$_POST[field]` VARCHAR( $_POST[field_length] ) NULL DEFAULT '$_POST[default_value]';";
				$db->query($sql1);
				
				if(!empty($_POST['is_search']))
				{	
					$sql2="ALTER TABLE `".$table_name."` ADD INDEX ( `$_POST[field]` )";
					$db->query($sql2);
				}
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$id = $_GET['id']*1;
				$table_name=$config['table_pre']."defind_".$id;
				$item="";
				if($_POST['display_type']>2)
				{
					foreach($_POST['name'] as $key=>$val)
					{
						$displayorder=$_POST['displayorder'][$key];
						$displayorder=$displayorder?$displayorder*1:rand(1,99999999);
						if($val and $displayorder)
						{
							$arr[]=$displayorder."|".$val;
						}
					}
					$item=implode(',',$arr);
				}
				
				$sql = "update ".PROPERTYVALUE." set `field`='$_POST[field]',`field_name`='$_POST[field_name]',`field_desc`='$_POST[field_desc]',`is_required`='$_POST[is_required]',`default_value`='$_POST[default_value]',`field_type`='$_POST[field_type]',`field_length`='$_POST[field_length]',`is_buy_item`='$_POST[is_buy_item]',`display_type`='$_POST[display_type]',`item`='$item',`is_search`='$_POST[is_search]',`property_id`='$id',`statu`='$_POST[statu]' where id='$_POST[id]'";
				$db->query($sql);
				
				if($_POST['oldName']!=$_POST['field'])
				{
					$sql="ALTER TABLE `$table_name` CHANGE `$_POST[oldName]` `$_POST[field]` VARCHAR( $_POST[field_length] ) NULL DEFAULT '$_POST[default_value]'";
					$db->query($sql);
				}
				
				if($_POST['is_search']!=$_POST['old_is_search'])
				{	
					if(!empty($_POST['is_search']))
					{
						$sql="ALTER TABLE `$table_name` ADD INDEX ( `$_POST[field]` )";
						$db->query($sql);
					}
					else
					{
						$sql="ALTER TABLE `$table_name` DROP INDEX `$_POST[field]` ";
						$db->query($sql);
					}
				}
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=product&s=property.php&operation=list&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".PROPERTYVALUE." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
			
			$str=explode(',',$de['item']);
			foreach($str as $v)
			{
				$str1[]=explode('|',$v);
			}
			$de['items']=$str1;
			
			$tpl->assign("de",$de);
		}
		$fieldtype = array ( 'varchar' => '字符(varchar)','int' => '整数(int)','text' => '大文本(text)');
		$tpl->assign("fieldtype",$fieldtype);
		$tpl->assign("config",$config);
		$tpl->assign("type",$lang['displayType']);
		$tpl->display("admin/property_value.htm");
	}
	else
	{	
		//删除
		if($_GET['delid'])
		{
			$db->query("delete from ".PROPERTY." where id='$_GET[delid]'");
			$db->query("delete from ".PROPERTYVALUE." where property_id='$_GET[delid]'");
			$table_name=$config['table_pre']."defind_".$_GET['delid'];
			$db->query("DROP TABLE `$table_name`");//删除生成的表
			msg("?m=product&s=property.php");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					
					$sql="delete from ".PROPERTY." where id in ($id)";
					$db->query($sql);
					$sql="delete from ".PROPERTYVALUE." where property_id in ($id)";
					$db->query($sql);
					foreach($_POST["chk"] as $val)
					{
						$table_name=$config['table_pre']."defind_".$val;
						$db->query("DROP TABLE `$table_name`");//删除生成的表
					}
				}
				if($_POST['name'])
				{
					foreach($_POST['name'] as $key=>$list)
					{
						if(!empty($list))
						{
							$displayorder=$_POST['displayorder'][$key];
							$displayorder=$displayorder?$displayorder*1:"255";
							$db->query("update ".PROPERTY." set name='$list',displayorder='$displayorder' where id='$key'");		
						}
					}
				}
				if($_POST['newname'])
				{
					foreach($_POST['newname'] as $key=>$list)
					{
						if(!empty($list))
						{
							$displayorder=$_POST['newdisplayorder'][$key];
							$displayorder=$displayorder?$displayorder*1:"255";
							$sql="insert into ".PROPERTY." (`name`,`displayorder`) values ('$list','$displayorder')";
							$db->query($sql);
							$id=$db->lastid();
							creat_table($id);
						}
					}
				}									
				msg("?m=product&s=property.php");
			}
		}
		//===============================================
		$sql="select * from ".PROPERTY." order by displayorder";
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
		$tpl->assign("de",$de);
		$tpl->display("admin/property.htm");
	}

?>
