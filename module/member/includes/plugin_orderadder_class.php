<?php

class orderadder
{
	var $db;
	function orderadder()
	{
		global $db;	
		$this -> db     = & $db;
	}
	function get_orderadder($id=NULL)
	{
		global $buid;	
		if(empty($id)||!is_numeric($id))
			return false;
		else
		{
			$sql="select * from ".USODADDER." where id=$id";
			$this -> db->query($sql);
			$re=$this -> db->fetchRow();
					  
			return $re;
		}		
	}
	function get_orderadderlist()
	{
		global $buid;	
		$sql="select * from ".USODADDER." where userid=$buid";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function add_orderadder()
	{			  	
		global $buid;
		if(!empty($_POST['submit'])&&(empty($_POST['name'])||empty($_POST['province'])||empty($_POST['city'])||empty($_POST['address'])||empty($_POST['zip'])))
			return 'error';
		$sql="select * from userid=$buid";
		$num=$this->db->num_rows();
	    if($num==5)
	    {
			return false;
		}
		$_POST['zip']*=1;
		$sql="insert into ".USODADDER."(`userid` ,`name` ,`provinceid` ,`cityid` ,`areaid` ,`area` 
		,`address` ,`zip` ,`tel` ,`mobile`)values($buid,'$_POST[name]','$_POST[province]',
		'$_POST[city]','$_POST[area]','$_POST[t]','$_POST[address]','$_POST[zip]','$_POST[tel]','$_POST[mobile]')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function edit_orderadder($id=NULL)
	{
		global $buid;
		if($id)
		{
			if(!empty($_POST['submit'])&&(empty($_POST['name'])||empty($_POST['province'])||empty($_POST['city'])||empty($_POST['address'])||empty($_POST['zip'])))
			return 'error';
			
			$_POST['zip']*=1;
			$sql="UPDATE ".USODADDER." SET `name` = '$_POST[name]',`provinceid` = '$_POST[province]',`cityid` = '$_POST[city]', `areaid` = '$_POST[area]', `area` = '$_POST[t]', `address` = '$_POST[address]',`zip` = '$_POST[zip]',`tel` = '$_POST[tel]',`mobile` = '$_POST[mobile]' WHERE  `id` ='$id' ";
			$this -> db->query($sql);	
			echo $id;
			return $id;
		}
	}
	function del_orderadder($id=NULL)
	{
		global $buid;	
		if(is_numeric($id))
		{
			$tel=$_POST['tel1'].'-'.$_POST['tel2'].'-'.$_POST['tel3'];
			$sql="delete from ".USODADDER."  where id=$id ";
			$flag=$this -> db->query($sql);		 
			return $flag;
		}
	}
}
?>
