<?php 
class activity
{
	var $db;
	function activity()
	{
		global $db;	
		$this -> db     = & $db;
	}
	//添加活动
	function add_activity()
	{
		$sql="INSERT INTO ".ACTIVITY." (`title` ,`desc` ,`ads_code` , `start_time` ,`end_time` ,`templates` ,`create_time` ,`status`,`displayorder` )VALUES ( '$_POST[title]','$_POST[desc]', '$_POST[ads_code]','".strtotime($_POST['stime'])."','".strtotime($_POST['etime'])."', '$_POST[templates]','".time()."','$_POST[status]','255')";
		$this->db->query($sql);
	}
	//修改活动
	function edit_activity()
	{
		$sql="UPDATE ".ACTIVITY."  SET `title` = '$_POST[title]',`desc` = '$_POST[desc]',
		`ads_code` = '$_POST[ads_code]',`start_time` =  '".strtotime($_POST['stime'])."',`end_time` = '".strtotime($_POST['etime'])."',`templates` = '$_POST[templates]',`status` = '$_POST[status]' 
		WHERE `id` ='$_POST[id]'  ";	
		$this->db->query($sql);
	}
	//获取所有活动列表
	function get_activity_list()
	{
		$sql="select id,title,start_time,end_time from ".ACTIVITY." where status>0 ";
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows =$this->db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",20";
		//=====================
		$this->db->query($sql);
		$re['list']=$this->db->getRows();
		$re['page']=$page->prompt();	
		return $re;
	}
	
	function save_activity_product()
	{
		global $buid;
		$id=$_POST['id']*1;
		
		$sql="select user from ".ALLUSER." where userid='$buid'";
		$this->db->query($sql);
		$user=$this->db->fetchField('user');
		
		foreach($_POST['chk'] as $val)
		{
			$sql="select status from ".ACTIVITYPRODUCT." where product_id='$val' and activity_id=$id";
			$this->db->query($sql);
			$status=$this->db->fetchField('status');
			if($status==3)
			{
				$sql="UPDATE ".ACTIVITYPRODUCT."  SET `status` = '4' WHERE `product_id` ='$val' and `activity_id` ='$id'  ";	
				$this->db->query($sql);	
				
				$sql="update ".PRO." set promotion_id='1' where id=$val";	
				$re=$this->db->query($sql);
			}
			else
			{
				$sql="select pname from ".PRO." where id='$val'";
				$this->db->query($sql);
				$pname=$this->db->fetchField('pname');
				
				$sql="INSERT INTO ".ACTIVITYPRODUCT."(`activity_id` ,`product_id` ,`product_name` ,`member_id` ,`member_name` ,`status` ,`create_time`) VALUES ('$id', '$val', '$pname', '$buid', '$user', '1','".time()."')";
				$re=$this->db->query($sql);
				
				$sql="update ".PRO." set promotion_id='1' where id=$val";	
				$re=$this->db->query($sql);
			}
			
		}
		return $re;
	}
}
?>