<?php

class invoice
{
	var $db;
	function invoice()
	{
		global $db;	
		$this -> db     = & $db;
	}
	function get_invoice($id=NULL)
	{
		global $buid;	
		if(empty($id)||!is_numeric($id))
			return false;
		else
		{
			$sql="select * from ".INVOICE." where id=$id";
			$this -> db->query($sql);
			$re=$this -> db->fetchRow();	  
			return $re;
		}		
	}
	function get_defaultinvoice()
	{
		global $buid;	
		$sql="select * from ".INVOICE." where uid='$buid' and checked='1'";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();
		return $re;
	}
	function get_invoicelist()
	{
		global $buid;	
		$sql="select * from ".INVOICE." where uid=$buid";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function add_invoice()
	{			  	
		global $buid;
		
		if(!empty($_POST['act'])&&$_POST['type']==1&&(empty($_POST['company1'])||empty($_POST['number'])||empty($_POST['address'])||empty($_POST['telephone'])||empty($_POST['bank'])||empty($_POST['account'])))
		{
			return 'error';
		}
		if(!empty($_POST['act'])&&$_POST['rise']==1&&empty($_POST['company']))
		{
			return 'error';
		}
		$sql="select * from ".INVOICE." where uid=$buid";
		$this -> db->query($sql);
		$num=$this->db->num_rows();
	   	if($num>=5)
	    {
			return false;
		}
		$d=$num?"0":"1";
		$company=$_POST['type']?$_POST['company1']:($_POST['rise']?$_POST['company']:"");
		$rise=$_POST['type']?"1":$_POST['rise'];
		$number=$_POST['type']?$_POST['number']:"";
		$address=$_POST['type']?$_POST['address']:"";
		$telephone=$_POST['type']?$_POST['telephone']:"";
		$bank=$_POST['type']?$_POST['bank']:"";
		$account=$_POST['type']?$_POST['account']:"";
		$sql="insert into ".INVOICE."(`uid` ,`type` ,`rise` ,`content`,`company` ,`number` ,`address` ,`telephone`,`bank`,`account`,`checked`)values('$buid','$_POST[type]','$rise','$_POST[content]','$company','$number','$address','$telephone','$bank','$account','$d')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function edit_invoice($id=NULL)
	{
		global $buid;
		if(!empty($_POST['act'])&&$_POST['type']==1&&(empty($_POST['company1'])||empty($_POST['number'])||empty($_POST['address'])||empty($_POST['telephone'])||empty($_POST['bank'])||empty($_POST['account'])))
		{
			return 'error';
		}
		if(!empty($_POST['act'])&&$_POST['rise']==1&&empty($_POST['company']))
		{
			return 'error';
		}
		if($id)
		{
			$company=$_POST['type']?$_POST['company1']:($_POST['rise']?$_POST['company']:"");
			$rise=$_POST['type']?"1":$_POST['rise'];
			$number=$_POST['type']?$_POST['number']:"";
			$address=$_POST['type']?$_POST['address']:"";
			$telephone=$_POST['type']?$_POST['telephone']:"";
			$bank=$_POST['type']?$_POST['bank']:"";
			$account=$_POST['type']?$_POST['account']:"";
			$sql="UPDATE ".INVOICE." SET `type` = '$_POST[type]',`rise` = '$rise',`content` = '$_POST[content]',`company` = '$company',`number` = '$number',`address` = '$address',`telephone` = '$telephone',`bank` = '$bank',`account` = '$account' WHERE  `id` ='$id' ";
			$this -> db->query($sql);	
		}
	}
	function edit_default($id=NULL)
	{
		global $buid;
		if($id)
		{
			$sql="UPDATE ".INVOICE." SET `checked` = '0' WHERE  `uid` ='$buid' ";
			$this -> db->query($sql);	
			$sql="UPDATE ".INVOICE." SET `checked` = '1' WHERE  `id` ='$id' ";
			$this -> db->query($sql);	
		}
	}
	function del_invoice($id=NULL)
	{
		global $buid;	
		if(is_numeric($id))
		{
			$sql="delete from ".INVOICE."  where id=$id ";
			$flag=$this -> db->query($sql);		 
			return $flag;
		}
	}
}
?>
