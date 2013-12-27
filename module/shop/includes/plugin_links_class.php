<?php
class links
{
	var $db;
	var $tpl;
	var $page;
	function links()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function add_link()
	{
		global $buid;
		if(empty($_POST['name']) && empty($_POST['url']))
		{
			return 'error';
		}
		$_POST['displayorder']=$_POST['displayorder']*1;
		$sql="insert into ".SHOPLINK." (shop_id,name,url,`desc`,displayorder,status) VALUES ('$buid','$_POST[name]','$_POST[url]','$_POST[desc]','$_POST[displayorder]','0')";
		$this->db->query($sql);
		$id=$this -> db->lastid(); 
		return $id;

	}
	
	function edit_link($id=NULL)
	{
		global $buid;
		if(empty($_POST['name']) && empty($_POST['url']))
		{
			return 'error';
		}
		$_POST['displayorder']=$_POST['displayorder']*1;
		$sql="UPDATE ".SHOPLINK." SET `name` = '$_POST[name]',`url` = '$_POST[url]',`desc` = '$_POST[desc]',`displayorder` = '$_POST[displayorder]' WHERE `id` = '$id' and `shop_id` = '$buid' ";
		 $this -> db->query($sql);
		 return $id;
	}
	
	function get_link($id=NULL)
	{	
		$sql="select * from ".SHOPLINK." where id = $id";
		$this -> db->query($sql);
		return $this -> db->fetchRow();	  
	}
	
	function link_list()
	{
		global $buid;
		$sql="SELECT * FROM ".SHOPLINK." WHERE shop_id='$buid' order by displayorder asc,id desc ";
		$this->db->query($sql);
		return $this->db->getRows();
	}
	
	function del_link($id)
	{
		global $buid;
		$sql="delete from ".SHOPLINK." WHERE id='$id'"; 
		$this->db->query($sql);
	}
	
}
?>
