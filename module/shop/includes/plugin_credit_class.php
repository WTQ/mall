<?php

class credit
{
	var $db;
	function credit()
	{
		global $db;	
		$this -> db     = & $db;
	}
	function get_user_credit()
	{
		global $buid;	
		$sql="select sellerpoints,buyerpoints from ".ALLUSER." where userid='$buid' ";
		$this -> db->query($sql);
		$re=$this -> db->fetchRow();	  
		return $re;
				
	}
	function get_user_comment_goodbad_count($type="buyer",$goodbad=NULL,$time=NULL,$sign=NULL)
	{
		global $buid;
		if(is_numeric($goodbad))
		{
			$where=" and goodbad='$goodbad'";	
		}
		if(!empty($time) && !empty($time))
		{
			$where.=" and uptime $sign '$goodbad'";	
		}
		if($type=="buyer")
		{
			$where.=" and puid <> '$buid'";	
		}
		elseif($type=="seller")
		{
			$where.=" and puid = '$buid'";	
		}
		$sql="select count(*) as count from ".PCOMMENT." where fromid='$buid' $where";
		$this -> db->query($sql);
		$count=$this -> db->fetchField('count');	  
		return $count;
	}
	
}
?>
