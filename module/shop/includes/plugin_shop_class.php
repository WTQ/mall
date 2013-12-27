<?php
class shop
{
	var $db;
	var $tpl;
	var $page;
	function shop()
	{
		global $db;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	//获取店铺分类名
	function GetShopCatName($id)
	{
		if(!empty($id))
		{
			$sql = "select name,parent_id from ".SHOPCAT." where id ='$id' ";
			$this->db->query($sql);
			$v=$this->db->fetchRow();
			$str[]=$val=$v['name'];
			if($v['parent_id']!=0)
			{
				$str[]=$this->GetShopCatName($v['parent_id']);
			}
		}
		if(is_array($str))
			$val=implode(' ',array_reverse($str));
		
		return $val;
	}
	
	//获取店铺分类
	function GetShopCatList()
	{
		$sql="select id,name from ".SHOPCAT."  where parent_id=0 order by displayorder ,id";
		$this->db->query($sql);
		return $this->db->getRows();
	}
	
	//获取店铺商品数量
	function GetProductNum($id)
	{
		$sql="select count(*) as num from ".PRO." where userid='$id'";
		$this->db->query($sql);
		return $this->db->fetchField('num');	
	}
	//获取店铺类型列表
	function GetShopGradeList()
	{
		$sql="select * from ".SHOPGRADE;
		$this->db->query($sql);
		return $this->db->getRows();
	}
	//获取店铺类型
	function GetShopGrade($id)
	{
		$sql="select * from ".SHOPGRADE." where id='$id'";
		$this->db->query($sql);
		return $this->db->fetchRow();
	}
	//添加店铺类型
	function AddShopGrade()
	{
		$time=time();
		$sql="insert into ".SHOPGRADE." (name,fee,`desc`,status,create_time) values ('$_POST[name]','$_POST[fee]','$_POST[des]','$_POST[status]','$time')";
		$this->db->query($sql);
	}
	//修改店铺类型
	function EditShopGrade($id)
	{
		$sql="update ".SHOPGRADE." set name='$_POST[name]',`desc`='$_POST[desc]',status='$_POST[status]',fee='$_POST[fee]' where id='$id'";
		$this->db->query($sql);
	}
	//获取店铺列表
	function GetShopList($str="")
	{
		if(!empty($_GET['name']))
			$scl.=" and (user like '%$_GET[name]%')";
			
		if(!empty($_GET['shop_name']))
			$scl.=" and (company like '%$_GET[shop_name]%')";	
		
		if(!empty($_GET['id']))
			$scl.=" and userid='$_GET[id]'";
			
		if(!empty($_GET['province']))
			$scl.=" and provinceid='$_GET[province]'";
		if(!empty($_GET['city']))
			$scl.=" and cityid='$_GET[city]'";
		if(!empty($_GET['area']))
			$scl.=" and areaid='$_GET[area]'";
		
		if($_SESSION['province'])
			$scl.=" and provinceid='$_SESSION[province]'";
		if($_SESSION['city'])
			$scl.=" and cityid='$_SESSION[city]'";
		
		if(!empty($_GET['catid']))
		{
			foreach($_GET['catid'] as $key=>$val)
			{
				if($key==0)
				$scl.="and ( catid like '$val%'";
				else
				$scl.=" or catid like '$val%'";
			}
			$scl.=") ";
		}
		if($_GET['grade'])
		{
			$grade=implode(",",$_GET['grade']);
			$scl.=" and grade in ($grade)";
		}
		//==================================
		$sql="SELECT * from ".SHOP."  WHERE 1 $scl $str order by userid desc";
		
		//分页
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
		$sql .= "  limit ".$page->firstRow.",".$page->listRows;
		$this->db->query($sql);
		$de['list'] = $this->db->getRows();
		foreach($de['list'] as $key=>$v)
		{
			//获取当前店铺类型
			$sql="select name from ".SHOPGRADE." where id='$v[grade]'";
			$this->db->query($sql);
			$grade=$this->db->fetchField('name');
			$de['list'][$key]['grade']=$grade;
			$de['list'][$key]['cat']=$this->GetShopCatName($v['catid']);
			//获取当前店铺商品数量
			$sql="select count(*) as num from ".PRO." where userid='$v[userid]'";
			$this->db->query($sql);
			$num=$this->db->fetchField('num');
			$de['list'][$key]['product_num']=$num;
		}
		$de['page'] = $page->prompt();
		return $de;
	}
	//获取店铺信息
	function GetShop($id)
	{
		$sql="select * from ".SHOP." where userid='$id'";
		$this->db->query($sql);
		return $this->db->fetchRow();
	}
	//修改店铺信息
	function EditShop($id)
	{
		$_POST['stime']=strtotime($_POST['stime']);
		$_POST['etime']=strtotime($_POST['etime']);
		$time=time();
		$credit=NULL;
		if($_POST['credit'])
		{
			foreach($_POST['credit'] as $v)
			{
				$credit=$credit+$v;
			}
		}
		$earnest=$_POST['earnest']*1;
		$catid=$_POST['catid']?$_POST['catid']:"0";
		$catid=$catid?$catid:$_POST['oldcatid'];
		$credit=$credit?$credit:"0";
		$sql="update ".SHOP." set statu='$_POST[statu]',stime='$_POST[stime]',etime='$_POST[etime]',template='$_POST[template]',shop_statu='$_POST[shop_statu]',credit='$credit',catid='$catid',view_times='$_POST[view]',grade='$_POST[grade]' ,earnest=earnest+$earnest where userid='$id'";
		
		$re=$this->db->query($sql);	
		if(!empty($earnest))
		{
			$sql="select id from ".SHOPEARNEST." where shop_id='$id'";
			$this->db->query($sql);	
			$shop_id=$this->db->fetchField('id');
			if(empty($shop_id))
			{
				$sql="insert into ".SHOPEARNEST." (shop_id,money,content,admin,create_time) 
					values ('$id','$earnest','','$_SESSION[ADMIN_USER]','$time')";
				$re=$this->db->query($sql);
			}
			else
			{
				$sql="update ".SHOPEARNEST." set money=money+$earnest,content='$_POST[con]',admin='$_SESSION[ADMIN_USER]',create_time='$time' where shop_id='$id'";
				$re=$this->db->query($sql);
			}
			
		}
	}
	
	//添加修改幻灯片
	function update_slide()
	{
		global $buid;
		foreach($_POST['slideurl'] as $val)
		{
			if($val!=="http://")
			{
				$slideurl[]=$val;
			}
		}
		$slide=array_filter($_POST['slide']);
		$slide=implode(',',$slide);
		$slideurl=implode(',',$slideurl);
		$this->db->query("select shop_id from ".SSET." where shop_id='$buid'");
		$shop_id=$this->db->fetchRow();
		if($shop_id)
		{
			$sql="update ".SSET." set shop_slide='$slide',shop_slideurl ='$slideurl' where shop_id='$buid'";
		}
		else
		{
			$sql="insert into ".SSET." (shop_id,shop_slide,shop_slideurl) values ('$buid','$slide','$slideurl')";
		}
		if($slide)
		$re=$this->db->query($sql);
	}
	//获取幻灯片
	function get_slide()
	{
		global $buid;
		$sql="select `shop_slide`,`shop_slideurl` from ".SSET." where shop_id='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		$de['slide']=explode(',',$re['shop_slide']);
		$de['slideurl']=explode(',',$re['shop_slideurl']);
		return $de;
	}
	
	//申请开店
	function update_user()
	{
		global $config,$buid,$buser;$catid=NULL;$ssql=NULL;
		$pn=time();
		//--------------------------------------
		$this->db->query("select userid from ".SHOP." where userid='$buid'");
		$ure=$this->db->fetchRow();
		$uid=$ure['userid'];
		
		$str="";
		if($_POST['grade'])
		{
			$str= ",grade='$_POST[grade]'";	
		}
		$catid=$_POST['catid'];
		if(!empty($_POST['tcatid'])){
			$catid=$_POST['tcatid'];
			
		}
		if(!empty($_POST['scatid'])){
			$catid=$_POST['scatid'];
		}
		if(!empty($_POST['sscatid'])){
			$catid=$_POST['sscatid'];
		}
	
		$shop_statu=$_POST['shop_statu']?$_POST['shop_statu']:"0";
		
		if(!empty($uid))
		{
			$sql="UPDATE ".SHOP." SET user='$buser',company='$_POST[company]',tel='$_POST[tel]',provinceid='$_POST[province]',addr='$_POST[addr]',cityid='$_POST[city]',areaid='$_POST[area]',area='$_POST[t]',main_pro='$_POST[main_pro]',uptime='$pn',create_time='$pn',logo='$_POST[logo]',shop_statu='$shop_statu' $str $ssql WHERE userid='$buid'";
			
			$re=$this->db->query($sql);
			
			$sql="update ".SSET." set shop_logo='$_POST[shop_logo]',shop_banner='$_POST[shop_banner]',shop_title='$_POST[shop_title]',shop_keywords='$_POST[shop_keywords]',shop_description='$_POST[shop_description]' where shop_id='$buid'";
			$re=$this->db->query($sql);
		}
		else
		{
			$sql="insert into ".SHOP." (company,tel,provinceid,addr,cityid,areaid,area,userid,user,logo,main_pro,grade,catid,uptime,create_time,shop_statu) VALUES ('$_POST[company]','$_POST[tel]','$_POST[province]','$_POST[addr]','$_POST[city]','$_POST[area]','$_POST[t]','$buid','$buser','$_POST[logo]','$_POST[main_pro]','$_POST[grade]','$catid','$pn','$pn','0')";
			$re=$this->db->query($sql);
			
			$sql="insert into ".SSET." (shop_id,shop_logo,shop_banner,shop_title,shop_keywords,shop_description) values ('$buid','$_POST[shop_logo]','$_POST[shop_banner]','$_POST[shop_title]','$_POST[shop_keywords]','$_POST[shop_description]')";
			$re=$this->db->query($sql);
		}
		$intro=$_POST['intro'];
		$fp=fopen($config['webroot'].'/config/shop_data/shop_data_'.$buid.'.txt','w');
		fwrite($fp,$intro,strlen($intro));
		fclose($fp);
		return $re;
	}
	
	//获取店铺信息
	function get_shop_info($id)
	{
		if(empty($id)) return NULL;
			
		global $config;$catname=NULL;$catv=array();

		$sql="select b.*,a.regtime,a.lastLoginTime,a.logo as plogo,a.ip,sellerpoints,buyerpoints,a.name
			from ".ALLUSER." a left join ".SHOP." b on a.userid=b.userid WHERE a.userid='$id'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		
		$sql="select * from ".POINTS." order by id";
		$this ->db ->query($sql);
		$de=$this ->db->getRows();
		foreach($de as $k=>$v)
		{
			$ar=explode('|',$v['points']);
			if($re['sellerpoints']<$ar[1] and $re['sellerpoints']>=$ar[0])
			{
				$re["sellerpointsimg"]=$v['img'];
			}
			if($re['buyerpoints']<$ar[1] and $re['buyerpoints']>=$ar[0])
			{
				$re["buyerpointsimg"]=$v['img'];
			}
		}
		return $re;
	}
	
	//获取店铺简介
	function get_shop_detail($buid)
	{
		global $config;
		$fn=$config['webroot'].'/config/shop_data/shop_data_'.$buid.'.txt';
		@$fp=fopen($fn,'r');
		@$con=fread($fp,filesize($fn));
		@fclose($fp);
		return $con;
	}
	//获取店铺配置
	function get_shop_setting()
	{
		global $buid;
		$sql="select `shop_logo`,`shop_banner`,`shop_title`,`shop_keywords`,`shop_description` from ".SSET." where shop_id='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	//获取当前会员商品订单总数
	function get_all_count($table,$array="",$type="1")
	{
		global $buid;
		if(is_array($array))
		{
			foreach($array as $k=>$v)
			{
				if($table==ORDER )
				{	
					if($type=="1")
					{
						if($v=="all")
						{
							$str=" and buyer_id!=''";
						}
						else
						{
							$str=" and buyer_id!='' and status=$v";
						}
					}
					else
					{
						if($v=="all")
						{
							$str=" and seller_id!=''";
						}
						elseif($v=="4")
						{
							$str=" and seller_id!='' and status=$v and buyer_comment!='1'";
						}
						else
						{
							$str=" and seller_id!='' and status=$v";
						}	
					}
				}
				else
				{
					$str=" and statu=$v ";
				}
				$sql="select count(*) as count from ".$table." where userid=$buid $str ";
				$this->db->query($sql);
				$count[$k]=$this->db->fetchField('count');
			}
		}
		else
		{
			$sql="select count(*) as count from ".$table." where fromid=$buid";
			$this->db->query($sql);
			$count=$this->db->fetchField('count');
		}
		return $count;;
	}
	//获取店铺评分
	function get_shop_comment()
	{
		global $buid;
		$sql="select avg(item1) as a,avg(item2) as b,avg(item3) as c from ".UCOMMENT." where byid = '$buid'";
		$this->db->query($sql);
		$u=$this->db->fetchRow();
		$u['aw']=$u['a']/5*100;
		$u['bw']=$u['b']/5*100;
		$u['cw']=$u['c']/5*100;
		return $u;;
	}
	
	//获取认证状态
	function GetCertification()
	{
		global $buid;
		$sql="select shop_auth,shopkeeper_auth,shop_auth_pic,shopkeeper_auth_pic from ".SHOP." where userid='$buid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re;
	}
	
	//认证状态
	function Certification()
	{
		global $buid;
		
		if($_POST['shop_auth_pic'])
		{
			$str=" ,shop_auth=0";
		}
		if($_POST['shopkeeper_auth_pic'])
		{
			$str=" ,shopkeeper_auth=0";
		}
		$sql="UPDATE ".SHOP." SET shop_auth_pic='$_POST[shop_auth_pic]',shopkeeper_auth_pic='$_POST[shopkeeper_auth_pic]' WHERE userid='$buid'";
		$re=$this->db->query($sql);
	}
	
	function GetShopStatus($id)
	{
		$sql="select shop_statu from ".SHOP." where userid='$id'";
		$this->db->query($sql);
		$shop_statu=$this->db->fetchField('shop_statu');
		return $shop_statu;
	}
}
?>