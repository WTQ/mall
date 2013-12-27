<?php
class report
{
	var $db;
	function report()
	{
		global $db;	
		$this -> db     = & $db;
	}
	function get_report_subject_type()
	{
		$sql="select `id`,`type_name`,`desc` from ".REPORTS." where type_id=0 and state=1";
		$this -> db->query($sql);
		$re=$this -> db->getRows();
		return $re;
	}
	function get_report_subject($id=NULL)
	{
		if($id)
		{
			$sql="  and type_id = '$id' ";	
		}
		$sql="select * from ".REPORTS." where state=1 $sql ";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	  
		return $re;
	}
	
	function get_product_info($id=NULL)
	{
		global $buid;	
		if(empty($id)||!is_numeric($id))
			return false;
		else
		{
			$sql="select pname,id from ".PRO." where id=$id";
			$this -> db->query($sql);
			$product_info=$this -> db->fetchRow();	 
			return $product_info;
		}		
	}
	
	function add_report()
	{			  	
		global $buid;
		
		if(empty($_POST['content']))
		{
			return 'error';
		}
		$subject=explode(',',$_POST['subject_name']);
		$subject[0]*=1;
		
$sql="insert into ".REPORT."(`userid`,`user`,`pid`,`pname`,`subject_id`,`subject_name`,`content`,`pic`,`datetime`,`shop_id`,`state`,`handle_type`,`handle_message`,`handle_datetime`) values ('$buid','$_COOKIE[USER]','$_POST[pid]','$_POST[pname]','$subject[0]','$subject[1]','$_POST[content]','$_POST[pic]','".time()."','$buid','0','0','','".time()."')";
		$this -> db->query($sql);	
		$id=$this -> db->lastid(); 
		return $id;
	}
	
	function get_reportlist()
	{
		global $buid;	
		$sql="select a.* ,b.type_name from ".REPORT." a left join ".REPORTS." b on a.subject_id = b.id where shop_id=$buid order by id desc";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
	
	function get_myreportlist()
	{
		global $buid;	
		$sql="select a.* ,b.type_name from ".REPORT." a left join ".REPORTS." b on a.subject_id = b.id where userid=$buid order by id desc";
		$this -> db->query($sql);
		$re=$this -> db->getRows();	
		return $re;
	}
}

?>