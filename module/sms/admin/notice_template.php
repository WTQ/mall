<?php
	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
	
			//添加
			if($_POST["act"]=='save')
			{
				if($_GET['type'])
				{
					$type=$_GET['type'];	
				}
				else
				{
					$type="0";	
				}
				
				$sql="insert into ".MAILMOD." (subject,message,title,type) values ('$_POST[subject]','$_POST[message]','$_POST[title]','$type')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".MAILMOD." set message='$_POST[message]',subject='$_POST[subject]',title='$_POST[title]' where id='$_POST[id]' ";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=sms&s=mobile_template.php&$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="SELECT * FROM ".MAILMOD." WHERE id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		$tpl->assign("config",$config);
	}
	else
	{
		//删除
		if($_GET['delid'])
		{
			$sql="delete from ".MAILMOD."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=sms&s=mobile_template.php&$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".MAILMOD." where id in ($id)";
					$db->query($sql);
				}
				$getstr=implode('&',convert($_GET));
				msg("?m=sms&s=mobile_template.php&$getstr");
			}
		}	
		//获取
		if($_GET['type'])
		{
			$sqls=" and type='$_GET[type]'";	
		}
		else
		{
			$sqls=" and type=0";	
		}
		$sql="select id,subject,title,flag from ".MAILMOD." where 1 $sqls order by id desc";
		$db->query($sql);
		$de['list']=$db->getRows();
	}
	
	$tpl->assign("de",$de);
	$tpl->display("admin/notice_template.htm");
	
?>