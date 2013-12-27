<?php

	include_once("$config[webroot]/includes/page_utf_class.php");
	include_once($config['webroot']."/module/activity/includes/plugin_activity_class.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit" or $_GET['operation']=='batch')
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$_POST['statu']=empty($_POST['statu'])?0:$_POST['statu'];
			$_POST['pic']=empty($_POST['pic'])?'':$_POST['pic'];
			$_POST['catid']=empty($_POST['catid'])?0:$_POST['catid'];
			$catid = $_POST['sub_catid']>0?$_POST['sub_catid']:$_POST['catid'];
			include_once($config['webroot'].'/lib/allchar.php');
			$str=c(trim($_POST['name']));
			$fstr=substr($str,0,1);
			$time=time();
	
			if($_POST["act"]=='batch')
			{
				foreach(explode("\r\n",$_POST['name']) as $na)
				{
					$str=c(trim($na));
					$fstr=substr($str,0,1);
					$sql="insert into ".BRAND." (name,catid,logo,displayorder,status,create_time,char_index,hits) values ('$na','$catid','".$_POST['pic']."','255','".$_POST['status']."','$time','$fstr','0')";
					$db->query($sql);
				}	
			}
			//添加
			if($_POST["act"]=='save')
			{
				$sql="insert into ".BRAND." (name,catid,logo,displayorder,status,create_time,char_index,hits) values 
			('$_POST[name]','$catid','".$_POST['pic']."','255','".$_POST['status']."','$time','$fstr','$_POST[hits]')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".BRAND." set name='".$_POST['name']."',catid='$catid',logo='".$_POST['pic']."',status='".$_POST['status']."',create_time='$time',char_index='$fstr',hits='$_POST[hits]'
			where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=brand&s=brand.php$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".BRAND." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		
		//获取分类
		$sql="select id,catname from ".BRANDCAT." where parent_id=0 order by displayorder";
		$db->query($sql);
		$cat=$db->getRows();
		foreach($cat as $key=>$val)
		{
			$sql="select * from ".BRANDCAT." where parent_id='$val[id]' order by displayorder";
			$db->query($sql);
			$cat[$key]['scat']=$db->getRows();	
		}
		$tpl->assign("cat",$cat);
		$tpl->assign("district",GetDistrict());
		$tpl->assign("config",$config);
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".BRAND."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			msg("?m=brand&s=brand.php$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".BRAND." where id in ($id)";
					$db->query($sql);
				}
				if($_POST['displayorder'])
				{
					foreach($_POST['displayorder'] as $key=>$list)
					{
						$db->query("update ".BRAND." set displayorder='$list' where id='$key'");		
					}
				}
			}
			else
			{
				if(is_array($_POST['chk']))
				{
					foreach($_POST['chk'] as $val)
					{
						if($_POST['submit']==$lang['rc'])
						{
							$db->query("update ".BRAND." set status='2' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_open'])
						{
							$db->query("update ".BRAND." set status='1' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_close'])
						{
							$db->query("update ".BRAND." set status='0' where id='$val'");
						}
					}
				}
			}
			msg("?m=brand&s=brand.php$getstr");
		}	
		//获取
		$sql="select b.*,c.catname from ".BRAND." b left join ".BRANDCAT." c on c.id=b.catid order by displayorder , id  desc ";
		//=============================
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query($sql);
			$page->totalRows = $db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		$pages = $page->prompt();
		//=====================
		$db->query($sql);
		$de['list']=$db->getRows();
		$de['page']=$page->prompt();
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/brand.htm");

?>