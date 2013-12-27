<?php

	include_once("$config[webroot]/includes/page_utf_class.php");

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$time=time();
			$_POST['sell_amount']=$_POST['sell_amount']?$_POST['sell_amount']:"0";
			//添加
			if($_POST["act"]=='save')
			{
				$sql="insert into ".TG." (`catid`,`name`,`content`,`pic`,`market_price`,`price`,`hits`,`sell_amount`,`limit_quantity`,`virtual_quantity`,`status`,`create_time`,`stock`,`provinceid`,`cityid`,`areaid`,`area`,`displayorder`) values 
			('$_POST[catid]','$_POST[name]','$_POST[content]','$_POST[pic]','$_POST[market_price]','$_POST[price]','$_POST[hits]','$_POST[sell_amount]','$_POST[limit_quantity]','$_POST[virtual_quantity]','$_POST[status]','$time','$_POST[stock]','$_POST[province]','$_POST[city]','$_POST[area]','$_POST[t]','255')";
				$db->query($sql);
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				$sql="update ".TG." set name='$_POST[name]',catid='$_POST[catid]',content='$_POST[content]',pic='$_POST[pic]',market_price='$_POST[market_price]',price='$_POST[price]',sell_amount='$_POST[sell_amount]',limit_quantity='$_POST[limit_quantity]',virtual_quantity='$_POST[virtual_quantity]',stock='$_POST[stock]',status='$_POST[status]',hits='$_POST[hits]',provinceid='$_POST[province]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]' where id='".$_POST['id']."'";
				$db->query($sql);
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=tg&s=tg.php$getstr");
		}
		//信息
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".TG." where id='$_GET[editid]'";
			$db->query($sql);
			$de=$db->fetchRow();
		}
		
		//获取分类
		$sql="select id,catname from ".TGCAT." where parent_id=0 order by displayorder";
		$db->query($sql);
		$cat=$db->getRows();
		foreach($cat as $key=>$val)
		{
			$sql="select * from ".TGCAT." where parent_id='$val[id]' order by displayorder";
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
			$sql="delete from ".TG."  where id='$_GET[delid]'";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			msg("?m=tg&s=tg.php$getstr");
		}
		if($_POST['act']=='op')
		{
			if($_POST['submit']==$lang['btn_submit'])
			{
				if(is_array($_POST['chk']))
				{
					$id=implode(",",$_POST['chk']);
					$sql="delete from ".TG." where id in ($id)";
					$db->query($sql);
				}
				if($_POST['displayorder'])
				{
					foreach($_POST['displayorder'] as $key=>$list)
					{
						$db->query("update ".TG." set displayorder='$list' where id='$key'");		
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
							$db->query("update ".TG." set status='2' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_open'])
						{
							$db->query("update ".TG." set status='1' where id='$val'");
						}
						elseif($_POST['submit']==$lang['btn_close'])
						{
							$db->query("update ".TG." set status='0' where id='$val'");
						}
					}
				}
			}
			msg("?m=tg&s=tg.php$getstr");
		}	
		//获取
		$sql="select b.*,c.catname from ".TG." b left join ".TGCAT." c on c.id=b.catid order by displayorder asc , id desc ";
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
	$tpl->display("admin/tg.htm");

?>