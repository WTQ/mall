<?php
class pro
{
	var $db;
	var $tpl;
	var $page;
	var $pic_save_path;	 
	
	function pro()
	{
		global $db;
		global $config;
		global $point_config;
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
		$this -> cTime  = date("Y-m-d H:i:s");
		$this->pic_save_path = "";//图片保存目录
		if(!empty($_POST['title'])||!empty($_POST['title']))
		{
			include_once($config['webroot'].'/includes/filter_class.php');
			$word=new Text_Filter();
			$_POST['detail']=$word->wordsFilter($_POST['detail'], $matche_row);
			$_POST['title']=$word->wordsFilter($_POST['title'], $matche_row);
		}
	}
	function get_brand($id,$sbrand=NULL)
	{
		//------------------------------------------------------------
		$id=explode(",",$id);
		if(!empty($id[2]))
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='$id[2]'");
		$brand=$this->db->fetchField('brand');
		
		if(empty($brand)&&!empty($id[1]))
		{
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='".$id[1]."'");
			$brand=$this->db->fetchField('brand');
		}
		if(empty($brand)&&!empty($id[0]))
		{
			$this->db->query("SELECT brand FROM ".PCAT." WHERE catid='".$id[0]."'");
			$brand=$this->db->fetchField('brand');
		}
		if(!empty($brand))
		{
			$sql="select * from ".BRAND." where id in ($brand) order by displayorder asc ";
			$this->db->query($sql);
			$re=$this->db->getRows();
			$op=NULL;
			foreach($re as $v)
			{
				if($v['name']==$sbrand)
					$s='selected="selected"';
				else
					$s=NULL;
				$op.='<option '.$s.' value="'.$v["name"].'">'.$v["name"].'</option>';
			}
			return '<select select="select" name="brand" id="brand" />'.$op.'</select>';
		}
		else
			return '<input maxlength="20" type="text" name="brand" value="'.$sbrand.'" />';
	}
	function getProTypeName($prod)
	{
		if(!empty($prod))
		{
			$sql = "select cat from ".PCAT." where catid in($prod)";
			$this->db->query($sql);
			$fieldlist="";
			while($v=$this->db->fetchRow())
			{
				if($v["cat"]!="")
				$fieldlist.=$v["cat"]."->";
			}
			$fieldlist = trim($fieldlist,"->");
		}
		return $fieldlist;
	}
	function get_user_common_lower_cat()
	{
		if(!empty($_GET['cat']))
		{
			$sql="select pid from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and id='$_GET[cat]' order by nums asc";
			$this->db->query($sql);
			$pid=$this->db->fetchField('pid');
			if($pid!='0')
				$num=$pid;
			else
				$num=$_GET['cat'];
			$sql="select * from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and pid='$num' order by nums asc";
			$this->db->query($sql);
			$re=$this->db->getRows();
			return $re;
		}
	}
	function add_user_common_cat($cid)
	{
		global $buid;
		//-------------------
		$cid=explode(",",$cid);
		$id=$cid[0];
		if(!empty($cid[1]))
			$id=$cid[1];
		if(!empty($cid[2]))
			$id=$cid[2];
		if(!empty($cid[3]))
			$id=$cid[3];
		$sql="select shop_id,common_cat from ".SSET." where shop_id='$buid'";
		$this->db->query($sql);
		$rec=$this->db->fetchRow();
		if($rec['shop_id'])
		{
			$buid*=1;
			if(empty($rec['common_cat']))
				$sql="update ".SSET." set common_cat='$id' where shop_id='$buid'";
			else
				$sql="update ".SSET." set common_cat=REPLACE(common_cat,',$id',''),common_cat=concat(common_cat,',$id') where shop_id='$buid'";
		}
		else
		{
			$buid=$buid?$buid:"0";
			$sql="insert into ".SSET." (shop_id,common_cat) values ('$buid',',$id')";
		}
		$re=$this->db->query($sql);
	}
	
	function rec_pro($id,$type)
	{	
		$sql="update ".PRO." set shop_rec=$type where id=$id ";
		$re=$this->db->query($sql);
		if($re)
			return 1;
		else
			return 0;
	}
	function get_num_by_user($uid)
	{
		$sql="select count(id) as num from ".PRO." WHERE userid='$uid'";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		return $re['num'];
	}
	
	function add_pro()
	{	
		global $config,$buid,$admin,$config;
		include_once("$config[webroot]/includes/tag_inc.php");
		$ifpay=empty($_SESSION["IFPAY"])?1:$_SESSION["IFPAY"];
		$title=htmlspecialchars($_POST["title"]);
		$_POST['amount']=empty($_POST['amount'])?100:$_POST['amount'];
		$_POST['market_price']*=1;
		$_POST['freight']*=1;//0为自设定，其它为运费模板。
		$_POST['freight_type']*=1;//1为买家承担，0为卖家承担
		$_POST['weight']*=1;//体积
		$_POST['cubage']*=1;//重量
		$_POST['post_price']*=1;
		$_POST['express_price']*=1;
		$_POST['ems_price']*=1;
		$_POST["detail"]=replace_outside_link($_POST["detail"]);//过虑内容中的a
		$_POST['point']*=1;
		$_POST['stime_type']=$_POST['stime_type']?$_POST['stime_type']:"1";
		$_POST['validTime']=$_POST['validTime']?$_POST['validTime']:"1";
		$_POST['ptype']=$_POST['ptype']?$_POST['ptype']:"0";
		$_POST['price']=$_POST['price']?$_POST['price']:($_POST['setmeal_p'][0]?$_POST['setmeal_p'][0]:$_POST['setmeal_p1'][0]);
		$_POST['amount']=$_POST['amount']?$_POST['amount']:"0";
		$buid=$buid?$buid:"0";
		
		if($_POST['stime_type']==1)
			$stime=time();
		elseif($_POST['stime_type']==2)
			$stime=strtotime($_POST['stime']);
		else
			$stime="0";
		$catid=$_POST['catid'];
		$pactidlist=$_POST['catid'];
		if(!empty($_POST['tcatid'])){
			$catid=$_POST['tcatid'];
			$pactidlist.= ",".$_POST['tcatid'];
		}
		if(!empty($_POST['scatid'])){
			$catid=$_POST['scatid'];
			$pactidlist.=",".$_POST['scatid'];
		}
		if(!empty($_POST['sscatid'])){
			$catid=$_POST['sscatid'];
			$pactidlist.=",".$_POST['sscatid'];
		}
		if(empty($_POST['custom_cat'])){
			$_POST['custom_cat']=0;	
		}
		//-----2的幂相加
		$credit=0;
		if($_POST['credit'])
		{
			foreach($_POST['credit'] as $v)
			{
				$credit=$credit+$v;
			}
		}
		//-----商品入库
		
		$pic='';
		$pic_more='';
		$img='';
		$_POST['pic']=array_unique($_POST['pic']);
		if(!empty($_POST['pic']))
		{
			foreach($_POST['pic'] as $val)
			{
				if($val)
				{
					$img[]=$val;	
				}
			}
			if(!empty($img))
			{
				$pic=$img['0'];
				$pic_more=implode(',',array_unique($img));
			}
		}
		
		$sql="INSERT INTO ".PRO." (userid,user,catid,pname,brand,market_price,price,uptime,pic,pic_more,stime_type,stime,validTime,province,city,amount,code,ptype,keywords,credit,custom_cat_id,point,freight_type,freight,post_price,express_price,ems_price,area,areaid) 
		VALUES
			('$buid','$_COOKIE[USER]','$catid','$title',
		'$_POST[brand]','$_POST[market_price]','$_POST[price]','$this->cTime','$pic','$pic_more'
		,'$_POST[stime_type]','$stime',
	  '$_POST[validTime]','$_POST[province]','$_POST[city]','$_POST[amount]','$_POST[code]',
		'$_POST[ptype]','$_POST[keywords]','$credit','$_POST[custom_cat]','$_POST[point]','$_POST[freight_type]','$_POST[freight]','$_POST[post_price]','$_POST[express_price]','$_POST[ems_price]','$_POST[t]','$_POST[area]')";
		$re=$this->db->query($sql);
		$id=$this->db->lastid();
		$re=$this->db->query("INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$_POST[detail]')");
		
		$pic = $admin->copypropic($id,$pic);
		
		if(!empty($_POST['spec']))
		{
			foreach($_POST['spec'] as $key=>$v)
			{
				$str1 = array();
				$str2 = array();
				foreach($v['sp_value'] as $val)
				{
					foreach($val as $num=>$s)
					{
						$str1[] = $s;
						$str2[] = $num;
					}
				}
				$name=implode(',',$str1);
				$pids=implode(',',$str2);
				
				if(!empty($v))
				{
					$sql="insert into ".SETMEAL." 
					(pid,setmeal,price,stock,sku,property_value_id) 
					values 
					('$id','".$name."','".$v['price']."','".$v['stock']."','".$v['sku']."','".$pids."')";
					$this->db->query($sql);
				}
			}	
		}
		//--------------------更新关键词
		add_tags($_POST['keyword'],1,$id);
		
		if($_POST['rec_pro']==1)
			$this->rec_pro($id,'1');
		//--------------------填加自定义字段
		$sql="select ext_table from ".PCAT." where catid='$catid'";
		$this->db->query($sql);
		$ext_table=$this->db->fetchField('ext_table');
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		$addfield = new AddField('product');
		$addfield->update_con($id,$ext_table);
		//---------------------
		return $re;
	}

	function add_product_batch()
	{	
		global $config,$buid,$admin,$config;
		include($config['webroot']."/includes/tag_inc.php");

		setlocale(LC_ALL, 'en_US.UTF-8');
		$fname = $config['webroot']."/uploadfile/preview/".$buid.".csv"; 
		$do = copy($_FILES['csv']['tmp_name'],$fname);
		$content = file_get_contents($fname);
		$content = iconv("GB2312","UTF-8//IGNORE",$content);
		$fp = fopen($fname, "w");
		fputs($fp, $content);
		fclose($fp);

		$sql="";
		$num=1;
		$file = fopen($fname,"r");
		$catid=$_POST['catid'];
		if(!empty($_POST['tcatid']))
			$catid=$_POST['tcatid'];
		if(!empty($_POST['scatid']))
			$catid=$_POST['scatid'];
		if(!empty($_POST['sscatid']))
			$catid=$_POST['sscatid'];
		$_POST['custom_cat']=$_POST['custom_cat']?$_POST['custom_cat']:"0";
		while ($data = fgetcsv ($file, 2000, ","))
		{
			if($num!=1)
			{
				$sql="INSERT INTO ".PRO." 
				(catid,userid,user,pname,brand,price,code,amount,keywords,uptime,statu,province,city,areaid,area,custom_cat_id) 
				VALUES
	 			('$catid','$buid','$_COOKIE[USER]','$data[0]','$data[4]','$data[1]','$data[2]','$data[3]','$data[5]','$this->cTime','$proCheck','$_POST[province]','$_POST[city]','$_POST[area]','$_POST[t]','$_POST[custom_cat]')";
				$this->db->query($sql);
				$id=$this->db->lastid();
				$sql="INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$data[6]')";
				$re=$this->db->query($sql);
				add_tags($data[5],1,$id);
			}
			$num++;
		}
		fclose($file);
		@unlink($fname);
	}

	function edit_pro()
	{
		global $config,$buid,$admin,$config;
		include_once("$config[webroot]/includes/tag_inc.php");
		$title=htmlspecialchars($_POST["title"]);
		
		$catid=$_POST['catid'];
		$pactidlist= $_POST['catid'];
		$id=$_POST["editID"]*1;
		$_POST['market_price']*=1;
		$_POST['freight']*=1;//0为自设定，其它为运费模板。
		$_POST['freight_type']*=1;//1为买家承担，0为卖家承担
		$_POST['weight']*=1;//体积
		$_POST['cubage']*=1;//重量
		$_POST['post_price']*=1;
		$_POST['express_price']*=1;
		$_POST['ems_price']*=1;
		$_POST['point']*=1;
		$_POST['stime_type']=$_POST['stime_type']?$_POST['stime_type']:"1";
		$_POST['validTime']=$_POST['validTime']?$_POST['validTime']:"1";
		$_POST['ptype']*=1;
		$_POST['price']=$_POST['price']?$_POST['price']:($_POST['setmeal_p'][0]?$_POST['setmeal_p'][0]:$_POST['setmeal_p1'][0]);
		$_POST['amount']=$_POST['amount']?$_POST['amount']:"100";
		$buid=$buid?$buid:"0";
		
		if(empty($catid)||empty($title))
			return 'Error';
		
		if($_POST['stime_type']==1)
			$stime=time();
		elseif($_POST['stime_type']==2)
			$stime=strtotime($_POST['stime']);
		else
			$stime="0";
			
		$credit=0;
		if($_POST['credit'])
		{
			foreach($_POST['credit'] as $v)
			{
				$credit=$credit+$v;
			}
		}

		if(!empty($_POST['tcatid'])){
			$catid=$_POST['tcatid'];
			$pactidlist.= ",".$_POST['tcatid'];
		}
		if(!empty($_POST['scatid'])){
			$catid=$_POST['scatid'];
			$pactidlist.= ",".$_POST['scatid'];
		}
		if(!empty($_POST['sscatid'])){
			$catid=$_POST['sscatid'];
			$pactidlist.= ",".$_POST['sscatid'];
		}	
		if(empty($_POST['custom_cat'])){
			$_POST['custom_cat']=0;	
		}

		$sub_sql = '';
		$pic='';
		$pic_more='';
		$img='';
		$_POST['pic']=array_unique($_POST['pic']);
		if(!empty($_POST['pic']))
		{
			foreach($_POST['pic'] as $val)
			{
				if($val)
				{
					$img[]=$val;	
				}
			}
			if(!empty($img))
			{
				$pic=$img['0'];
				$pic_more=implode(',',array_unique($img));
				$sub_sql.= $pic ?",pic='$pic'":",pic=''";
				$sub_sql.= $pic_more ?",pic_more='$pic_more'":",pic_more=''";
			}
		}
		
		$sql="UPDATE ".PRO."  SET
			point='$_POST[point]',pname='$title',catid='$catid',
			brand='$_POST[brand]',market_price='$_POST[market_price]',price='$_POST[price]',code='$_POST[code]',
			uptime='$this->cTime',stime_type='$_POST[stime_type]',stime='$stime',validTime='$_POST[validTime]',province='$_POST[province]',city='$_POST[city]',area='$_POST[t]',areaid='$_POST[area]',amount='$_POST[amount]',ptype='$_POST[ptype]',keywords='$_POST[keywords]',credit='$credit',freight='$_POST[freight]',freight_type='$_POST[freight_type]',weight='$_POST[weight]',cubage='$_POST[cubage]',post_price='$_POST[post_price]',express_price='$_POST[express_price]',ems_price='$_POST[ems_price]',custom_cat_id='$_POST[custom_cat]'".$sub_sql." WHERE id=$id and userid='$buid'";
		$re=$this->db->query($sql);
		
		
		if(!empty($_POST['spec']))
		{
			
			$sql="DELETE FROM ".SETMEAL." WHERE `pid` = $id";
			$this->db->query($sql);
			foreach($_POST['spec'] as $key=>$v)
			{
				$str1 = array();
				$str2 = array();
				foreach($v['sp_value'] as $val)
				{
					foreach($val as $num=>$s)
					{
						$str1[] = $s;
						$str2[] = $num;
					}
				}
				$name=implode(',',$str1);
				$pids=implode(',',$str2);
				
				if(!empty($v))
				{
					$sql="insert into ".SETMEAL." 
					(pid,setmeal,price,stock,sku,property_value_id) 
					values 
					('$id','".$name."','".$v['price']."','".$v['stock']."','".$v['sku']."','".$pids."')";
					$this->db->query($sql);
				}
			}	
		}
		
		//-----------------更新产品详情
		$sql="select proid from ".PRODETAIL." where proid='$id'";
		$this->db->query($sql);
		if($this->db->num_rows())
			$re=$this->db->query("UPDATE ".PRODETAIL." SET detail='$_POST[detail]' WHERE proid='$id'");
		else
			$re=$this->db->query("INSERT INTO ".PRODETAIL." (userid,proid,detail) VALUES ('$buid','$id','$_POST[detail]')");
		//--------------------添加关键词
		edit_tags($_POST['keyword'],1,$id);
		//--------------------推荐产品
		if(!empty($_POST['rec_pro'])&&$_POST['rec_pro']==1)
			$this->rec_pro($id,'1');
		else
			$this->rec_pro($id,'0');
			
		//--------------------填加自定义字段
		$sql="select ext_table from ".PCAT." where catid='$catid'";
		$this->db->query($sql);
		$ext_table=$this->db->fetchField('ext_table');
		
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		$addfield = new AddField('product');
		$addfield->update_con($id,$ext_table);
		//---------------------
		return $re;
	}
	
	function set_pro_statu($pid,$statu)
	{
		$sql="update ".PRO." set statu='$statu' where id='$pid'";
		$this->db->query($sql);
	}
	
	function pro_list($statu)
	{
		global $buid,$config;
		if(isset($statu))
		{
			if($statu==1)
				$sqls="and statu>=1";
			else
				$sqls="and statu='$statu'";
		}
		$sql="select id,pname,uptime,pic,statu,price,amount,code,shop_rec
		from ".PRO."  where userid='$buid' $sqls order by uptime desc";
		//=============================
	  	$page = new Page;
		$page->listRows=10;
		if (!$page->__get('totalRows')){
			$this->db->query("select count(id) as num from ".PRO." where userid='$buid' $sqls ");
			$num=$this->db->fetchRow();
			$page->totalRows = $num['num'];
		}
        $sql .= "  limit ".$page->firstRow.",10";
		//=====================
		$this->db->query($sql);
		$re["list"]=$this->db->getRows();
		$re["page"]=$page->prompt();
		return $re;
	}
	
	function update_pro($id)
	{
		global $buid,$config;
		if($id=="all")
			$sql="update ".PRO." SET uptime='$this->cTime' where userid='$buid'";
		else
			$sql="update ".PRO." SET uptime='$this->cTime' where id=$id";
		$this->db->query($sql);
		
	}
	function del_pro($id,$uid=NULL)
	{
		global $config,$buid;
		if(!empty($uid))
			$buid=$uid;
		//---------------------------------------------------
		include_once("$config[webroot]/includes/tag_inc.php");
		del_tag($id,1);
		//---------------------------------------------------
		$this->db->query("select catid,pic from ".PRO." where id='$id'");
		$re=$this->db->fetchRow();
		
		//---------------------------------------------------
		$this->db->query("delete from  ".PRO." where id='$id'");
		$this->db->query("delete from  ".PRODETAIL." where proid='$id'");
	
		//----------------------------------------------------删除自定义数据
		$sql="select ext_table from ".PCAT." where catid='$catid'";
		$this->db->query($sql);
		$ext_table=$this->db->fetchField('ext_table');
		
		include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
		$addfield = new AddField('offer');
		$addfield->delete_con($id,$ext_table);
		//----------------------------------------------------删除图片
		if($re['pic']!='')
		{
			$pic = explode( ',',$re['pic'] );
			foreach($pic as $i)
			{
				@unlink($config['webroot'].'/'.$this->pic_save_path."$i");
				@unlink($config['webroot'].'/'.$this->pic_save_path.substr($i,0,strrpos($i,'/')+1).'big_'.substr($i,strrpos($i,'/')+1));
			}
		}

	}
	
	function pro_detail($id)
	{
		if($id)
		{	
			global $config,$buid;
			include_once("$config[webroot]/includes/tag_inc.php");
			$sql="select a.*,b.detail from ".PRO." a left join ".PRODETAIL." b on a.id=b.proid where a.id=$id and a.userid='$buid'";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			
			$sql="select ext_table from ".PCAT." where catid='$re[catid]'";
			$this->db->query($sql);
			$re['ext_table']=$this->db->fetchField('ext_table');
			
			if(strlen($re['catid'])>8)
				$re['sscatid']=$re['catid'];
			if(strlen($re['catid'])>6)
				$re['scatid']=substr($re['catid'],0,8);	
			if(strlen($re['catid'])>4)
				$re['tcatid']=substr($re['catid'],0,6);
			$re['catid']=substr($re['catid'],0,4);
			
			//=====================================
		
			$re['keyword']=get_tags($id,1);
			//======================================
			
			$re['pic'] = $re['pic_more']?explode(',',$re['pic_more']):'';
			
			$sql="select * from ".SETMEAL." where pid=$id and property_value_id !='' ";
			$this->db->query($sql);
			$re['porperty']=$this->db->getRows();
			foreach($re['porperty'] as $key=>$val)
			{
				$a=explode(',',$val['setmeal']);
				$b=explode(',',$val['property_value_id']);
				$c=array();
				$d='';
				foreach($a as $k=>$v)
				{
					$num=$b[$k];
					$c[$k][$num]=$v;
					$d.=$num;
				}
				$re['porperty'][$key]['setmeal']=$c;
				$re['porperty'][$key]['property_value_id']=$d;
			}
			return $re;
		}
	}
	//==============================
	function detail($pid)
	{
		global $buid,$config;
		$days=array('7','15','30','90','180','365');
		$sql="update ".PRO." set read_nums=read_nums+1 where id='$pid'";
		$this->db->query($sql);
		
		$sql="select a.*,b.detail from ".PRO." a left join ".PRODETAIL." b on a.id=b.proid where a.id='$pid'";
		$this->db->query($sql);
		$prod=$this->db->fetchRow();
		
	
		$sql="select * from ".SETMEAL." where pid=$pid and property_value_id !='' ";
		$this->db->query($sql);
		$prod['porperty']=$this->db->getRows();
		foreach($prod['porperty'] as $key=>$val)
		{
			$a=explode(',',$val['setmeal']);
			$b=explode(',',$val['property_value_id']);
			$c=array();
			$d='';
			foreach($a as $k=>$v)
			{
				$num=$b[$k];
				$c[$k][$num]=$v;
				$d[]=$num;
			}
			
			$re['porperty'][$key]['setmeal']=$c;
			$re['porperty'][$key]['property_value_id']=$d;
		}
			
		
		if(empty($prod['validTime']))
			$prod['validTime']=2;
		if($prod['validTime']==6)
			$prod['have_time']=6;
		else
			$prod['have_time']=strtotime($prod['uptime'])+$days[$prod['validTime']]*24*3600-time();	
		if(!empty($prod['pic'])){
		//====================================================
			
			$prod['pic_more'] = explode(",",$prod['pic_more']);		
		}
		//=======产品类型========================================
		$ptype=explode('|',$config['ptype']);
		$prod["ptype"]=$ptype[$prod['ptype']];
		return $prod;
	}
	
	function shop_pro_list()
	{
		global $config;
		switch ($_GET['sort'])
		{
			case "sell_amount":
			{	
				$sort=' order by sell_amount desc';break;
			}
			case "_sell_amount":
			{	
				$sort=' order by sell_amount';break;
			}
			case "price":
			{	
				$sort=' order by price';break;
			}
			case "_price":
			{	
				$sort=' order by price desc';break;
			}
			case "uptime":
			{	
				$sort=' order by uptime desc';break;
			}
			case "_uptime":
			{	
				$sort=' order by uptime';break;
			}
			case "read_nums":
			{	
				$sort=' order by read_nums desc';break;
			}
			case "_read_nums":
			{	
				$sort=' order by read_nums';break;
			}
			default:
			{
				$sort=' order by id desc';break;
			}
		}
		
		if($_GET['keyword'])
		{
			$keyword=trim($_GET['keyword']);
			$str.=" and pname  like '%$keyword%' ";	
		}
		if($_GET['price1'])
		{
			$str.=" and price >= $_GET[price1] ";	
		}
		if($_GET['price2'])
		{
			$str.=" and price <= $_GET[price2]";	
		}
		if(!empty($_GET['cat']))
		{	
			
			$sql="select id from ".CUSTOM_CAT." where userid='$_GET[uid]' and type='1' and pid='".$_GET['cat']."' order by nums asc";
			$this->db->query($sql);
			$de=$this->db->getRows();
			$cats=$_GET['cat'];
			foreach($de as $val)
			{
				if($val['id'])
				$cats=$cats.','.$val['id'];
			}
			$sql="select id,pname,userid,market_price,price,user,pic,sell_amount from ".PRO." where userid='$_GET[uid]' and custom_cat_id in ($cats) and statu>0 $str $sort";
			$count_sql = "select count(*) as num from ".PRO." where userid='$_GET[uid]' and custom_cat_id in ($cats) and statu>0 $str";
		}
		else
		{
			$sql="select a.id,a.userid,market_price,pname,pic,uptime,price,sell_amount,a.user from ".PRO." a where 
				a.userid='$_GET[uid]' and a.statu>0 $str $sort";
			$count_sql = "select count(*) as num  from ".PRO." a where a.userid='$_GET[uid]' and a.statu>0 $str";
		}
		//-------------------------------------------------
		include_once($config['webroot']."/includes/page_utf_class.php");
		$page = new Page;
		$page->url='shop.php';
		$page->listRows=24;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($count_sql);
			$page->totalRows = $this->db->fetchField('num');
		}
		$sql .= "  limit ".$page->firstRow.", ".$page->listRows;
		$infoList['page']=$page->prompt();
		$infoList['count']=$page->totalRows;
		if($page->nowPage==1){
			$pre="<a class='disable'>上一页</a>";
		}else{
			$pre="<a class='prePage' href='$page->url?firstRow=".($this->nowPage-2) * ($this->listRows)."&totalRows=$page->totalRows$page->parameter'>上一页</a>";
		}
		if($page->nowPage==$page->totalPages){
			$next="<a class='disable'>下一页</a>";
		}else{
			$next="<a class='nextPage' href='$page->url?firstRow=".($page->nowPage) * ($page->listRows)."&totalRows=$page->totalRows$page->parameter'>下一页</a>";
		}
		$infoList['pages']="<span>$page->nowPage / $page->totalPages</span>".$pre.$next;
		
		//--------------------------------------------------
		$this->db->query($sql);
		while($pl=$this->db->fetchRow())
		{
			$pl['img'] = $this->get_cover_img($pl['pic']);
			if($pl['pic']=='')
				$pl['nopic']=1;
			$infoList["list"][]=$pl;
		}
		return $infoList;
	}

	//##产品封面
	function get_cover_img( $pic='' )
	{		
		global $config;
		if($pic=='' )
			return NULL;
		$pic = explode( ',',$pic );
		foreach( $pic as $v ){
			return $v;
		}
		return '';
	}

	//*********************merge**************************
	function pro_merge_user($array)
	{
		$old_uid=$array['old_uid'];
		$new_uid=$array['new_uid'];
		$new_user=$array['new_user'];

		$sql = "update ".PRO." set userid='$new_uid' where userid='$old_uid'";
		$this->db->query($sql);
		$sql = "update ".PRODETAIL." set userid='$new_uid' where userid='$old_uid'";
		$this->db->query($sql);
		$sql = "update ".ORDER." set buid=IF(buid='$old_uid','$new_uid',buid),suid=IF(suid='$old_uid','$new_uid', suid)   where buid='$old_uid' or suid='$old_uid'";
		$this->db->query($sql);
	}
}
?>