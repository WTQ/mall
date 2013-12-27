<?php

	if($_GET['operation']=="add" or $_GET['operation']=="edit")
	{
		if($_POST['act'])
		{	
			unset($_GET['operation']);
			unset($_GET['s']);
			unset($_GET['m']);
			$_POST['isindex']=isset($_POST['isindex'])?1:0;
			$_POST['brand']=empty($_POST['brand'])?NULL:implode(",",$_POST['brand']);
			$_POST['ext_field_cat']*=1;
			$_POST['isbuy']=isset($_POST['isbuy'])?1:0;
			$_POST['is_setmeal']=isset($_POST['is_setmeal'])?1:0;;
			$_POST['commission']*=1;
			if(!empty($_POST['ext_field_cat']))
				$ext_table=$config['table_pre'].'defind_'.$_POST['ext_field_cat'];
			else
				$ext_table=0;

			//添加
			if($_POST["act"]=='save')
			{	
				if($_POST["pid"]==0)
				{
					$sql="select max(catid) as catid from ".PCAT." where catid<9999";
					$db->query($sql);
					$id=$db->fetchField("catid");
					if(!$id)
						$id=1000;
					else
						$id=substr($id*1000,0,4)+1;
				}
				else
				{
					$s=$_POST["pid"]."00";
					$b=$_POST["pid"]."99";
					$sql="select max(catid) as catid from ".PCAT." where catid>$s and catid<$b";
					$db->query($sql);
					$id=$db->fetchField("catid");
					if(!$id)
						$id=$_POST["pid"]."01";
					else
						$id=$id+1;
				}
				foreach(explode("\r\n",$_POST['cat']) as $catv)
				{
					if(!empty($catv))
					{
						$sql="insert into ".PCAT." (`catid`,`cat`,`nums`,`isindex`,`pic`,`brand`,ext_table,ext_field_cat,commission,is_setmeal,isbuy) values 
							('$id','$catv','255','$_POST[isindex]','','$_POST[brand]','$ext_table','$_POST[ext_field_cat]','$_POST[commission]','$_POST[is_setmeal]','$_POST[isbuy]')";
						$db->query($sql);
						$id+=1;
					}
				}
			}
			//修改
			if($_POST["act"]=='edit' and is_numeric($_POST['id']))
			{
				
				if($_POST["pid"]!=substr($_POST['id'],0,strlen($_POST['id'])-2))
				{
					if($_POST["pid"]==0)
					{
						$sql="select max(catid) as catid from ".PCAT." where catid<9999";
						$db->query($sql);
						$id=$db->fetchField("catid");
						if(!$id)
							$id=1000;
						else
							$id=substr($id*1000,0,4)+1;
					}
					else
					{
						$s=$_POST["pid"]."00";
						$b=$_POST["pid"]."99";
						$sql="select max(catid) as catid from ".PCAT." where catid>$s and catid<$b";
						$db->query($sql);
						$id=$db->fetchField("catid");
						if(!$id)
							$id=$_POST["pid"]."01";
						else
							$id=$id+1;
					}
				}
				else
					$id=$_POST['id'];

				
				$sql="update ".PCAT." set catid='$id', cat='$_POST[cat]',isindex='$_POST[isindex]' ,pic='',isbuy='$_POST[isbuy]',brand='$_POST[brand]',ext_table='$ext_table',ext_field_cat='$_POST[ext_field_cat]',commission='$_POST[commission]',is_setmeal='$_POST[is_setmeal]' where catid='".$_POST['id']."'";
				$db->query($sql);
				
				if($_POST["pid"]!=substr($_POST['id'],0,strlen($_POST['id'])-2))
				{
					//如果当前类别下面带有子类别把子类别一起移过去
					$s=$_POST['id']."00";
					$b=$_POST['id']."99";
					$sql="update ".PCAT." set catid=replace(catid,$_POST[id],$id) where catid like '$_POST[id]%'";
					$re=$db->query($sql);
				}
				unset($_GET['editid']);
			}
			$getstr=implode('&',convert($_GET));
			msg("?m=product&s=product_cat.php&$getstr");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$sql="select * from ".PCAT." where catid='$_GET[editid]'";
			$db->query($sql);
			$re=$db->fetchRow();
			$re['cid']=substr($re['catid'],0,-2);
			$re['brands']=explode(',',$re['brand']);
			$tpl->assign("re",$re);
		}
		
		$sql="select* from ".BRANDCAT." where parent_id=0 order by displayorder ";
		$db->query($sql);
		$brand=$db->getRows();
		foreach($brand as $k=>$v)
		{
			$brand[$k]['brand']='0';
			$sql="select* from ".BRANDCAT." where parent_id=$v[id] order by displayorder ";
			$db->query($sql);
			$a=$db->getRows();
			foreach($a as $key=>$val)
			{
				$sql="select * from ".BRAND." where catid=$val[id] and status!=0  order by displayorder ";
				$db->query($sql);
				$a[$key]['brand']=$db->getRows();
				if($a[$key]['brand'])
				{
					$brand[$k]['brand']='1';
				}
			}
			$brand[$k]['cat']=$a;
		}
		$tpl->assign("brand",$brand);
		
		$sql="select * from ".PROPERTY;
		$db->query($sql);
		$property=$db->getRows();
		$tpl->assign("property",$property);
		
		$sql="select * from ".PCAT." where catid<9999 order by nums,catid";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $key=>$val)
		{
			$sql="select * from ".PCAT." where catid < '".$val['catid']."99' and catid > '".$val['catid']."00' order by nums,catid";
			$db->query($sql);
			$a=$db->getRows();
			foreach($a as $ke=>$va)
			{
				$sql="select * from ".PCAT." where catid < '".$va['catid']."99' and catid > '".$va['catid']."00' order by nums,catid";
				$db->query($sql);
				$a[$ke]['scat']=$db->getRows();
			}
			$de[$key]['scat']=$a;
		}
	}
	else
	{
		if($_GET['delid'])
		{
			$sql="delete from ".PCAT."  where catid like '$_GET[delid]%' ";
			$db->query($sql);
			unset($_GET['delid']);
			unset($_GET['s']);
			unset($_GET['m']);
			$getstr=implode('&',convert($_GET));
			msg("?m=product&s=product_cat.php&$getstr");
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
						$db->query("update ".PCAT." set cat='$list',nums='$displayorder' where catid='$key'");		
					}
				}
			}
			msg("?m=product&s=product_cat.php");
		}
	
		if($_GET['id'])
		{
			$id=$_GET['id']*1;
			$tsql=" and catid < '".$id."99' and catid >'".$id."00' ";
		}
		else
			$tsql=" and catid<9999";
			
		$sql="select * from ".PCAT." where 1 $tsql order by nums,catid";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $k=>$v)
		{
			$sql="select name from ".PROPERTY." where id='$v[ext_field_cat]'";
			$db->query($sql);
			$de[$k]['property']=$db->fetchField('name');
		}
	}
	$tpl->assign("de",$de);
	$tpl->display("admin/product_cat.htm");
?>