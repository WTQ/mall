<?php
include_once("$config[webroot]/module/payment/includes/payment_class.php");

class payment_api extends payment
{
	function payment_api()
	{
		parent::payment('admin');
	}
	
	function delete_by_uid($array)
	{
		$sql="select pay_uid from ".PUSER." where userid='$array[uid]'";
		$this->db->query($sql);
		$uid=$this->db->fetchField('pay_uid');
		if($uid)
		{
			$this->db->query("delete from ".PUSER." where pay_uid='$array[uid]'");
			$this->db->query("delete from ".ACCOUNTS." where pay_uid='$array[uid]'");
			$this->db->query("delete from ".CASHFLOW." where pay_uid='$array[uid]'");
			$this->db->query("delete from ".CASHPICKUP." where pay_uid='$array[uid]'");
		}
	}
}
?>