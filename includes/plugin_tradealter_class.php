<?php
class tradealter
{
	var $db;
	var $tpl;
	var $page;
	function tradealter()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	function list_subscribe($id="")
	{
		global $buid;
		if(empty($id))
		{
			$sql="select * from ".SUBSCRIBE." where userid='$buid' order by uptime desc";
			$this->db->query($sql);
			$re=$this->db->getRows();
		}
		else
		{
			$sql="select * from ".SUBSCRIBE." where id='$id'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
		}
		return $re;
	}
	function up_subscribe($sid='')
	{
		global $buid,$config;
		if(!empty($sid))
		{
			$nt=time();
			$sql="update ".SUBSCRIBE." set keywords='$_POST[keycon]',validity='$_POST[validity]',frequency='$_POST[frequency]',uptime='$nt',email='$_POST[email]' where id='$sid'";
			$this->db->query($sql);
		}
		else
		{	
			$nt=time();
			$sql="insert into ".SUBSCRIBE." (userid,keywords,validity,frequency,uptime,email) 
			values ( '$buid','$_POST[keycon]','$_POST[validity]','$_POST[frequency]','$nt','$_POST[email]')";
			$this->db->query($sql);
		}
	}
	function delete_subscribe($did="")
	{
		global $buid;
		$sql="delete  from ".SUBSCRIBE." where  id='$did'";
		$this->db->query($sql);
	}
}
?>
