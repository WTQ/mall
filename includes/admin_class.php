<?php
class admin{

	var $cTime;
	var $db;
	var $tpl;
	var $page;
	function admin()
	{
		global $db;
		global $config;
		global $tpl;
		
		$this -> cTime  = date("Y-m-d H:i:s");
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	} 
	//===========================================
	function is_login($action)
	{
		global $buid,$config;
		if(!$buid||!isset($_COOKIE["USER"]))
		{
			header("Location: $config[weburl]/login.php");
			exit();
		}
		if(empty($_SESSION["STATU"]))
		{
			$this->db->query("select statu from ".ALLUSER." WHERE userid='$buid'");
			$re=$this->db->fetchRow();
			$_SESSION["STATU"]=$re['statu'];
			if(empty($_SESSION["STATU"]))
				$_SESSION["STATU"]=2;
		}
		
		if($_SESSION["STATU"]<=-1&&$_GET['type']!='access_dine'&&$action!='logout')
		{
			header("Location: $config[weburl]/main.php?action=msg&type=access_dine");
			exit();
		}
		
		if(!empty($_SESSION["STATU"])&&$_SESSION["STATU"]==1&&$_GET['s']!="myshop"&&$_GET['s']!="cominfo"&&$action!="msg"&&$action!="logout"&&$action!="admin_personal")
		{
			header("Location: $config[weburl]/main.php?action=msg&type=active");
			exit();
		}
	}
	function msg($url,$str=NULL,$type=NULL,$time=NULL)
	{
		$str=urlencode($str);
		$url=urlencode($url);
		header("Location:?action=msg&str=$str&url=$url&time=$time&type=$type");
	}
	
	function who_view_myshop($uid)
	{
		$sql="select a.time,b.user,b.logo,b.userid from ".READREC." a , ".ALLUSER." b 
		where a.userid=b.userid and a.tid='$uid' and a.type='3' limit 0,9";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	
	function get_user_common_cat($id)
	{
		$cats=array();
		$sql="select shop_id,common_cat from ".SSET." where shop_id='$id'";
		$this->db->query($sql);
		$rec=$this->db->fetchRow();
		if(!empty($rec['shop_id']))
		{
			$cat=explode(",",$rec['common_cat']);
			foreach($cat as $v)
			{
				$vc=array();
				if(!empty($v))
				{
					$vc[]=substr($v,0,4);
					if(strlen($v)>4)
						$vc[]=substr($v,0,6);
					if(strlen($v)>6)
						$vc[]=substr($v,0,8);
					if(strlen($v)>8)
						$vc[]=$v;
					$key=implode(",",$vc);
					$cats[$key]=$this->getProTypeName($key);
				}
			}
			return $cats;
		}
		else
			return array();
	}
	function getProTypeName($prod)
	{	
		global $db;
		if(!empty($prod))
		{
			$sql = "select cat from ".PCAT." where catid in($prod)";
			$db->query($sql);
			$fieldlist="";
			while($v=$db->fetchRow())
			{
				if($v["cat"]!="")
				$fieldlist.=$v["cat"]."->";
			}
			$fieldlist = trim($fieldlist,"->");
		}
		return $fieldlist;
	}
	
	function check_shop_statu($uid)
	{
		global $config;
		$sql="SELECT shop_statu FROM ".SHOP." WHERE userid='$buid'";
		$this->db->query($sql);
		$shop_statu=$this->db->fetchField('shop_statu');
		return $shop_statu;
	}
	
	function check_myshop($uid=NULL)
	{
		global $buid;
		if(!empty($uid))
			$buid=$uid;
			
		$sql="SELECT * FROM ".SHOP." WHERE userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if($re)
		{
			return 2;//在企业信息存在，企业级会员。
		}
		else
			return 1;//企业信息不存
	}
	
	function clear_user_shop_cache()
	{
		global $config,$buid;
		$dir=$config['webroot'].'/cache/shop/'.get_userdir($buid).'/';
		$handle = opendir($dir); 
		while ($filename = readdir($handle))
		{
			if(!is_dir($dir.$filename))
				@unlink($dir.$filename);
		}
	}
	
	//----product---------------------------------------------------
	
	/**
	 * $type 1商城，2产品,3资讯,4展会,5视频,6相册
	 */
	//======Brad=======================
	function update_custom_cat($type='')
	{
		global $buid;
		foreach($_POST['name'] as $key=>$v)
		{
			if(!empty($_POST['cid'][$key]))
			{
				$sql="update ".CUSTOM_CAT." set name='$v',nums='".$_POST['nums'][$key]."' where
				id='".$_POST['cid'][$key]."'";
				$re=$this->db->query($sql);
			}
			elseif(!empty($v))
			{
				$sql="insert into ".CUSTOM_CAT." (userid,name,type,nums) values 
				('$buid','$v','$type','".$_POST['nums'][$key]."')";
				$re=$this->db->query($sql);
			}
		}
		if(is_array($_POST['sname']))
		{
			foreach($_POST['sname'] as $key=>$v)
			{
				if(!empty($_POST['scid'][$key])&&!empty($_POST['sname'][$key]))
				{
					$sql="update ".CUSTOM_CAT." set name='".$_POST['sname'][$key]."',nums='".$_POST['snums'][$key]."' where
					id='".$_POST['scid'][$key]."'";
					$re=$this->db->query($sql);
				}
			}
		}
		if(is_array($_POST['pid']))
		{
			foreach($_POST['pid'] as $key=>$v)
			{
				if(!empty($v)&&!empty($_POST['addsname'][$key]))
				{
					$sql="insert into ".CUSTOM_CAT." (userid,pid,name,type,nums) values 
					('$buid','".$_POST['pid'][$key]."','".$_POST['addsname'][$key]."','$type','".$_POST['addsnums'][$key]."')";
					$re=$this->db->query($sql);
				}
			}
		}
	}
	
	function add_custom_cat($type="")
	{
		global $config,$buid;
		$sql="insert into ".CUSTOM_CAT." (userid,name,type) values ('$buid','$_POST[name]','$type')";
		$re=$this->db->query($sql);
		msg("main.php?m=product&s=admin_product_cat");
	}
	function get_custom_cat_list($type="", $single="")
	{
		global $config,$buid;
		if($single>0)
		{
			$sql="select * from ".CUSTOM_CAT." where id='$single' and type='$type' order by nums asc";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
		}
		else
		{
			$sql="select * from ".CUSTOM_CAT." where userid='$buid' and type='$type' and pid=0 order by nums asc";
			$this->db->query($sql);
			$re=$this->db->getRows();
			for($i=0;$i<count($re);$i++)
			{
				$sql="select * from ".CUSTOM_CAT." where pid='".$re[$i]['id']."' and pid!=0 order by nums asc";
				$this->db->query($sql);
				$re[$i]['subcat']=$this->db->getRows();
			}
		}
		return $re;
	}

	function edit_custom_cat($type="",$editid="")
	{
		global $config,$buid;
		$sql="update ".CUSTOM_CAT." set name='$_POST[name]' where id='$editid' and userid='$buid'";
		$re=$this->db->query($sql);
		msg("main.php?m=product&s=admin_product_cat");
	}

	function del_custom_cat($deid="")
	{
		global $config,$buid;
		$sql="delete from ".CUSTOM_CAT." where id='$deid' and userid='$buid'";
		$re=$this->db->query($sql);
	}
	
	//---------------------------------------------------------
	
	function getCatName($table)
	{
		$sql="select * from $table where catid<9999 order by nums asc";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}

	//##产品封面
	function get_cover_img( $pic='' ){
		if( $pic=='' )
			return NULL;
		$pic = explode( ',',$pic );
		foreach( $pic as $v ){
				return $v;
		}
		return '';
	}

	function copypropic($id,$pic)
	{
		$save_path = implode( '/',explode( '-',date('y-m-d') ) ).'/';
		$dest = 'uploadfile/product/img/'."$save_path";
		if(!file_exists($dest))
			mkdirs($dest);	
			
		$ar=explode('|',$pic);
		@copy($ar[0],$dest.$id.'.jpg');
	}
	
	//##==================产品图片保存===================
	function add_pro_pic( $copy=false )
	{
		if(!empty($_POST['pic']))
		{	
			if( !isset($save_path) )
				$save_path = implode( '/',explode( '-',date('y-m-d') ) ).'/';
			$add_pic = array();
			$pic_list = explode(',',$_POST['pic']);
			
			$str=explode('/',$pic_list[0]);
			if($str[0]=='uploadfile')
			{
				$dest = 'uploadfile/product/'."$save_path";
				if(!file_exists($dest))
					mkdirs($dest);
				foreach( $pic_list as $key=>$i )
				{
					if(file_exists($i))
					{
						$str1=explode('/',$i);
						$bimg=$str1[0].'/'.$str1[1].'/'.$str1[2].'/big_'.$str1[3];
						$pic_id = $str1[3];
						if($copy==true)
						{
							$add_pic[] = $dest.$pic_id;
							@copy( $i,$dest.$pic_id );
							@copy( $bimg,$dest.'big_'.$pic_id );						
						}
						else
						{
							$desc_id = $pic_id;
							$add_pic[] = $dest.$desc_id;
							
							@rename( $i,$dest.$desc_id );
							@rename( $bimg,$dest.'big_'.$desc_id );
						}
					}
					else
						unset($pic_list[$key]);
				}
			}
			else
			{
				$add_pic=$pic_list;	
			}
		}
		
		if($add_pic!=null)
				return implode( ',',$add_pic );
			else
				return '';
	}
	//=========图片更新==============
	function update_pro_pic($pro_pic)
	{
		$pro_pic = $pro_pic==''?array():explode( ',',$pro_pic );
		//删除图片
		if(!empty( $_POST['dele_pic'] )){
		   $del_pic = explode( ',',$_POST['dele_pic'] );
			if($del_pic!=null){
				foreach( $del_pic as $key=>$pic_id ){
					if(array_search( $pic_id,$pro_pic )>-1){
						unset($pro_pic[array_search( $pic_id,$pro_pic )]);
						@unlink( "$pic_id" );
						@unlink( substr($pic_id,0,strrpos($pic_id,'/')+1).'big_'.substr($pic_id,strrpos($pic_id,'/')+1));
					}
				}
			}
		}
		//添加图片
		if(!empty($_POST['pic']))
		{	
			$new_pic = $this->add_pro_pic();
			$pro_pic = $new_pic==''?$pro_pic:array_merge( array_merge( $pro_pic,explode(',',$new_pic) ));
		}
		return $pro_pic;
	}
	
	
	function buy_adv($buid)
	{
		global $admin,$buid,$config;
		$t=time();
		$this->db->query("select * from ".ADVS." where id='$_GET[id]'");
		$ad=$this->db->fetchRow();
		
		if($ad!=null&&is_numeric($_POST['show_time'])&&intval($_POST['show_time'])>0)
		{
			$sql = "insert into ".ADVSCON." set userid='$buid',group_id='$_GET[id]',name='".addslashes($ad['name'])."',show_time='$_POST[show_time]',unit='$ad[unit]',ctime='$t'";
			$this->db->query($sql);
			$id=$this->db->lastid();
		}
		//-----------------------------------------支付
		include("$config[webroot]/module/payment/lang/$config[language].php");
		$post['type']=1;//直接到账
		$post['action']='add';//
		$post['buyer_email']=$buid;
		$post['seller_email']='admin@systerm.com';
		$post['order_id']=$id;//外部订单号
		$post['price']=$ad['price']*$_POST['show_time'];//订单总价，单价元
		$post['extra_param']='';//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
		$post['return_url']='main.php?action=admin_adv_list';//返回地址
		$post['notify_url']='main.php?action=admin_adv_list';//异步返回地址。
		$post['note']=$note[11];
		pay_get_url($post);//跳转至订单生成页面
		//----------------------------------------
	}

}
?>