<?php
class temp
{
	var $db;
	var $tpl;
	var $page;
	function temp()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function update_user_tem($temp)
	{	
		global $buid,$config;
		if($temp=='0')
		{
			$sql="UPDATE ".USER." SET template='' WHERE userid='$buid'";
			$this->db->query($sql);
			msg("main.php?m=shop&s=admin_template");
			die;
		}
		$sql="select id,temp_file from ".SHOPTEMP." where id='$temp'";
		$this->db->query($sql);
		$temp=$this->db->fetchRow();

		$sql="UPDATE ".USER." SET template='$temp[temp_file]' WHERE userid='$buid'";
		$this->db->query($sql);
		
		msg("main.php?m=shop&s=admin_template");
		die;
	}
	
	function user_temp_list()
	{	
		global $config,$buid;
		$sql="select id,name,temp_file,style from ".SHOPTEMP." where status>0 order by displayorder ASC";
		//----------------------------------------
		include_once("includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$this->db->query("select count(*) as num from ".SHOPTEMP."  where status>0");
			$page->totalRows=$this->db->fetchField('num');
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		//-----------------------------------------
		$this->db->query($sql);
		$templist['list']=$this->db->getRows();
		$default['id']='0';
		$default['name']='Default';
		$default['temp_file']='user_templates_default';
		$default['style']='Default';
		$templist['list'][]=$default;
		$templist['pages']=$page->prompt();
		return $templist;
	}
	
	function get_shop_nav($id=NULL)
	{	
		$sql="select * from ".SHOPN." where id=$id";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();	  
		return $re;
	}
	function get_shop_nav_content($id=NULL)
	{	
		$sql="select content,title from ".SHOPN." where id=$id";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();	  
		return $re;
	}
	
	function get_shop_nav_list()
	{
		global $buid;	
		$sql="select * from ".SHOPN." where shop_id=$buid order by sort";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function add_shop_nav()
	{			  	
		global $buid;
		if(empty($_POST['title']))
		{
			return 'error';
		}
		$sql="insert into ".SHOPN."(`title` ,`shop_id` ,`content` ,`sort`,`if_show` ,`add_time` ,`url` ,`new_open`)values('$_POST[title]','$buid','$_POST[content]','$_POST[sort]','$_POST[if_show]','".time()."','$_POST[url]','$_POST[new_open]')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function edit_shop_nav($id=NULL)
	{
		global $buid;
		if(empty($_POST['title']))
		{
			return 'error';
		}
		$sql="UPDATE ".SHOPN." SET `title` = '$_POST[title]',`content` = '$_POST[content]',`sort` = '$_POST[sort]',`if_show` = '$_POST[if_show]',`url` = '$_POST[url]',`new_open` = '$_POST[new_open]' WHERE  `id` = '$id' and `shop_id` = '$buid' ";
		$this -> db->query($sql);	
		return $id;
	}
	
	function del_shop_nav($id=NULL)
	{
		global $buid;	
		if(is_numeric($id))
		{
			$sql="delete from ".SHOPN."  where id=$id ";
			$flag=$this -> db->query($sql);		 
			return $flag;
		}
	}
	
	function add_cs()
	{			  	
		global $buid;
		unset($_POST['submit']);
	
		$this -> db->query("delete from ".CS."  where uid=$buid ");	
		
		foreach($_POST['prename'] as $key=>$val)
		{
			$type='0';
			$v['name']=$val;
			$v['tool']=$_POST['pretool'][$key];
			$v['num']=$_POST['prenum'][$key];
			if(!empty($v['name']) and !empty($v['tool']) and !empty($v['num']))
				$inserts[]="('$buid','$v[name]','$v[tool]','$v[num]','$type')";
			
		}
		foreach($_POST['aftername'] as $key=>$val)
		{
			$type='1';
			$v['name']=$val;
			$v['tool']=$_POST['aftertool'][$key];
			$v['num']=$_POST['afternum'][$key];
			if(!empty($v['name']) and !empty($v['tool']) and !empty($v['num']))
				$inserts[]="('$buid','$v[name]','$v[tool]','$v[num]','$type')";
			
		}
		if($inserts)
		{
			$sql="insert into ".CS."(uid,name,tool,number,type) values ".implode(',', $inserts);
			$this -> db->query($sql);	
		}
	}
	
	function get_cs()
	{
		global $buid;	
		$sql="select * from ".CS." where uid=$buid order by id";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		foreach($re as $key=>$val)
		{
			if($val['type']==1)
			{
				$de['after'][]=$re[$key];	
			}
			else
			{
				$de['pre'][]=$re[$key];
			}
		}
		return $de;
	}
	
	function del_cs()
	{
		global $buid;	
		$this -> db->query("delete from ".CS."  where id=$_POST[id] ");	
	
	}
}
?>
