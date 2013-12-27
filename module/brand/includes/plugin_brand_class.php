<?php
class brand
{
	var $db;
	var $tpl;
	var $page;
	function brand()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	function brand_index_list()
	{
		$sql="select * from ".BRANDCAT."  where 1 order by displayorder asc ";
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($re as $key=>$v)
		{
			$sql="select id,name from ".BRAND." where catid='$v[id]' and status >0 order by displayorder asc ";
			$this->db->query($sql);
			$re[$key]['sub']=$this->db->getRows();
		}
		return $re;
	}
	function brand_list()
	{	
		global $config;
		if(!empty($_GET['searchby']))
			$sql.=" and char_index='$_GET[searchby]'";
		if(!empty($_GET['id']))
		{	
			$id=!empty($_GET['etgid'])?$_GET['etgid']:$_GET['id'];
			$sqls="select id from ".BRANDCAT." where parent_id='$id'";
			$this->db->query($sqls);
			$de=$this->db->getRows();
			foreach($de as $key=>$val)
			{
				$str.=$val['id'].',';
			}
			$str=$str.$id;
			$sql.=" and catid in ($str) ";
		}
		$sql="select * from ".BRAND." where 1 $sql order by id desc";
		//=============================
		include_once("includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=20;
		$page->url=$config['weburl'];
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
		 $sql .= "  limit ".$page->firstRow.",20";
		//=====================
		$this->db->query($sql);
		$rs["lists"]=$this->db->getRows();
		$rs["pages"]=$page->prompt();
		return $rs;
	}
}
?>
