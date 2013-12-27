<?php
class album
{
	var $db;
	var $tpl;
	var $page;
	function album()
	{
		global $db;
		global $config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	function add_album_cat()
	{
		global $buid,$config;
		$catid=$_POST['catid'];
		if(!empty($_POST['tcatid']))
			$catid=$_POST['tcatid'];
		if(!empty($_POST['scatid']))
			$catid=$_POST['scatid'];
		if(empty($catid))
			$catid=0;
		$sql="insert into ".CUSTOM_CAT." (userid,sys_cat,name,type,des) 
				values ('$buid','$catid','$_POST[name]','6','$_POST[des]')";
		$re=$this->db->query($sql);
		return $re;
	}
	
	function get_album_cat_list($type="", $single="")
	{
		global $config,$buid;
		if ($single>0)
		{
			$sql="select * from ".CUSTOM_CAT." where id=$single and type='$type' order by nums asc";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
		}
		else
		{
			$sql="select * from ".CUSTOM_CAT." where userid='$buid' and type='$type' order by nums asc";
			$this->db->query($sql);
			$re=$this->db->getRows();
		}
		return $re;
	}
	
	function edit_album_cat($type="",$editid="")
	{
		global $config,$buid;
		$catid=empty($_POST['catid'])?0:$_POST['catid'];
		if(!empty($_POST['tcatid']))
			$catid=$_POST['tcatid'];
		if(!empty($_POST['scatid']))
			$catid=$_POST['scatid'];

		$sql="update ".CUSTOM_CAT." set name='$_POST[name]',sys_cat='$catid',des='$_POST[des]' where id='$editid' and userid='$buid'";
		$re=$this->db->query($sql);
		if(is_uploaded_file($_FILES['pic']['tmp_name']))
		{	
			$save_directory = $config['webroot']."/uploadfile/catimg/";
			makethumb($_FILES['pic']['tmp_name'] , $save_directory.$editid.".jpg" , 500 , 500);
			makethumb($_FILES['pic']['tmp_name'] , $save_directory."size_small/".$editid.".jpg" , 150 , 150);
			@unlink($save_directory.$editid.".jpg");
		}
		msg("main.php?m=album&s=admin_album_cat");

	}
	function del_album_cat($type="",$deid="")
	{
		global $config,$buid;

		$sql="delete from ".CUSTOM_CAT." where id='$deid' and userid='$buid'";
		$re=$this->db->query($sql);
	}
	function add_album()
	{
		global $config,$buid;
		$buid*=1;
		if($_SESSION['ADMIN_USER'])
			$buid=0;
		//图片上传，管理员上传时buid='';用于区分是会员上传还是管理员上传
		$_POST['con']=empty($_POST['con'])?NULL:$_POST['con'];
		$sql="insert into ".ALBUM." (userid,user,zname,con,`time`,catid) values
		 ('$buid','$_COOKIE[USER]','$_POST[name]','$_POST[con]','".time()."','$_GET[catid]')";
		$re=$this->db->query($sql);
		$id=$this->db->lastid();
		//上传
		if(is_uploaded_file($_FILES['pic']['tmp_name']))
		{	
			$save_directory = $config['webroot']."/uploadfile/zsimg/";
			makethumb($_FILES['pic']['tmp_name'] , $save_directory.$id.".jpg" , 500 , 500);
			makethumb($_FILES['pic']['tmp_name'] , $save_directory."size_small/".$id.".jpg" , 150 , 150);
		}
		if($_POST['act'])
		{
			$save_directory = $config['weburl']."/uploadfile/zsimg/";
			$js .= 'window.parent.SetUrl("'.$save_directory.$id.'.jpg");';
			$js .= 'window.parent.GetE("frmUpload").reset();';
			echo "<script>$js</script>";
		}
		return $id;
	}

	function add_multi_album()
	{
		global $config,$buid,$admin;
		
		$str_id=NULL;
		
		foreach($_POST['pic'] as $k=>$v)
		{
			if($v)
			{
				$sql="insert into ".ALBUM." (userid,user,zname,con,`time`,catid,pic) values 
				('$buid','$_COOKIE[USER]','$name','$con','".time()."','$_GET[catid]','$v')";
				$re=$this->db->query($sql);
				$id=$this->db->lastid();
				$str_id.=$id.",";
			}
			
		}
		return $str_id;
	}
	function get_the_first_albumid()
	{
		global $buid;
		$sql="select * from ".CUSTOM_CAT." where userid='$buid' and type=6";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if(!empty($re['id']))
			return $re['id'];
		else
		{
			$sql="insert into ".CUSTOM_CAT." (userid,sys_cat,name,type,des) 
			values ('$buid','0','default','6','default')";
			$re=$this->db->query($sql);
			$id=$this->db->lastid();
			return $id;
		}
	}
	function edit_album()
	{
		global $config,$buid;
		$sql="update ".ALBUM." set zname='$_POST[name1]',con='$_POST[con1]' where id='$_GET[editid]' and userid='$buid'";
		$re=$this->db->query($sql);
	}
	function del_album($id,$uid)
	{
		if($id)
		{
			global $config;
			$sql="select id,pic from ".ALBUM." where id='$id' and userid='$uid'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			if(!$re['id'])
				return;
			$sql="delete from ".ALBUM." where id='$id'";
			$re=$this->db->query($sql);
			@unlink($re['pic']);
		}
	}

	function album_list($catid='')
	{
		 global $buid;
		 if(!empty($catid))
		 	$sql=" and catid='$catid'";
         $sql="select * from ".ALBUM." where userid='$buid' $sql";
		//=============================
	  	$page = new Page;
		$page->listRows=18;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",18";
		//=====================
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	function album_detail($id)
	{
		if(empty($id))
			return NULL;
		$sql="select * from ".ALBUM." where id='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	function shop_album_detail()
	{
		//相册详情
		$sql = "select a.* from ".CUSTOM_CAT." a, ".USER." d where id='$_GET[id]'";
		$this->db->query($sql);
		$albumd=$this->db->fetchRow();
		//所有图片列表
		$sql="select * from ".ALBUM." where order by id desc ";
		$this->db->query($sql);
		$re['pic_list']=$this->db->getRows();
		$re['album_detail']=$albumd;
		return $re;
	}
	
	function shop_album_list()
	{
		global $config;
		$sql="select * from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='6' order by id desc";
		//-----------------------------------
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->listRows=16;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",$page->listRows";
		//-----------------------------------
		$this->db->query($sql);
		$sre=$this->db->getRows();
		$re['list']=$sre;
		$re['page']=$page->prompt();
		return $re;
	}

	//===============================================
	function album_merge_user($array)
	{
		$old_uid=$array['old_uid'];
		$new_uid=$array['new_uid'];
		$new_user=$array['new_user'];

		$sql = "update ".ALBUM." set userid='$new_uid',user='$new_user' where userid='$old_uid'";
		$this->db->query($sql);
	}
}
?>
