<?php

if(!$shop)
include("$config[webroot]/module/shop/includes/plugin_shop_class.php");

class shop_api extends shop
{
	function shop_api()
	{
		parent::shop();
	}
	
	function update_uid($array)
	{	
		;
	}
	
	function delete_by_uid($array)
	{
		$userid=$array['uid'];
		
		$this->db->query("select logo from ".SHOP." where userid='$userid'");
		$logo=$this->db->fetchField('logo');
		@unlink("../uploadfile/userimg/$logo.jpg");
		@unlink("../uploadfile/userimg/size_small/$logo.jpg");
		
		$this->db->query("delete from ".SHOP." where userid='$userid'");
		$this->db->query("delete from ".SSET." where shop_id='$userid'");
		$this->db->query("delete from ".CUSTOM_CAT." where userid='$userid'");
		$this->db->query("delete from ".UDOMIN." where shop_id='$userid'");

	}
}
?>