<?php
include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");

class product_api extends pro
{
	function product_api()
	{
		parent::pro();
	}
	
	function update_uid($array)
	{	
		$old_uid=$array['old_uid'];
		$new_uid=$array['new_uid'];

		$sql = "update ".PRO." set userid='$new_uid' where userid='$old_uid'";
		$this->db->query($sql);
		$sql = "update ".PRODETAIL." set userid='$new_uid' where userid='$old_uid'";
		$this->db->query($sql);
		/* 这里还没有加全 */
		
	}
	
	function delete_by_uid($array)
	{
		$this->db->query("select id,userid from ".PRO." where userid='$array[uid]'");
		$re=$this->db->getRows();
		foreach($re as $v)
		{
			$this->del_pro($v['id'],$v['userid']);//删除产品
			$this->db->query("delete from ".USODADDER." where userid='$v[userid]'");//删除收货地址
		}
		
		//**删除订单**/
		$sql="select order_id from ".ORDER." where order_id='$deid'";
		$this->db->query($sql);
		$ore=$this->db->getRows();
		foreach($ore as $v)
		{
			$sql="delete from ".ORDER." where order_id='$v[order_id]'";
			$this->db->query($sql);//删除指定的订单
			
			$sql="select order_id from ".ORDER." where order_id='$v[order_id]'";
			$this->db->query($sql);
			$oid=$this->db->fetchField('order_id');
			if(!$oid)
			{//当买家和卖家的订单全删除之后才删除订单的产品。
				$sql="delete from ".ORPRO." where order_id='$v[order_id]'";
				$this->db->query($sql);
			}
		}
	}
	
	function get_custom_cat_num_by_user($uid)
	{
		$sql="select count(id) as num from ".CUSTOM_CAT." WHERE userid='$buid' and type=1 and pid=0";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re['num'];
	}
}
?>