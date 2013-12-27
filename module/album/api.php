<?php
include("$config[webroot]/module/album/includes/plugin_album_class.php");

class album_api extends album
{
	function album_api()
	{
		parent::album();
	}
	
	function update_uid($array)
	{	
		$this->album_merge_user($array);
	}
	
	function delete_by_uid($array)
	{
		$this->db->query("select id,userid from ".ALBUM." where userid='$array[uid]'");
		$rec = $this->db->getRows();
		foreach($rec as $v)
		{
			$this->del_album($v['id'],$v['userid']);
		}
	}
}
?>