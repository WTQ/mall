<?php

class member
{
	var $db;
	function member()
	{
		global $db;	
		$this -> db     = & $db;
	}
	
	function get_member_detail($id)
	{
		global $buid,$config;
		if(empty($id))
			$id=$buid;
		$sql="select * from ".MEMBER." WHERE userid='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	
	function update_member($uid)
	{
		global $config,$buid;$logo=NULL;$ssql=NULL;
		if(empty($uid))
			$uid=$buid;
		
		$_POST['sex']=empty($_POST['sex'])?1:$_POST['sex'];
		
		$sql="UPDATE ".MEMBER." SET
		name='$_POST[name]',tel='$_POST[tel]',qq='$_POST[qq]',provinceid='$_POST[province]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]',sex='$_POST[sex]',
		msn='$_POST[msn]',skype='$_POST[skype]',position='$_POST[position]',logo='$_POST[logo]'
		WHERE userid='$uid'";
		
		$re=$this->db->query($sql);
		return $re;
	}
	
	
	function resetpass($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["oldpass"]) || empty($_POST["newpass"]) || empty($_POST["renewpass"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["oldpass"]))
		{
			return '1';	die;
		}
		if($_POST["newpass"]!=$_POST["renewpass"])
		{
			return '2';	die;
		}
		$sql="UPDATE ".MEMBER." SET password='".md5($_POST['newpass'])."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '3';
		
	}
	function resetemail($buid)
	{
		$sql="SELECT password FROM ".MEMBER." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(empty($_POST["pass"]) || empty($_POST["mail"]))
		{
			return '0';	die;
		}
		if($re['password']!=md5($_POST["pass"]))
		{
			return '1';	die;
		}
		$sql="UPDATE ".MEMBER." SET email='".$_POST['mail']."' WHERE userid='$buid'";
		$re=$this->db->query($sql);
		return '2';
	}
}
?>
