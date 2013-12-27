<?php
//-1删除的订单
//0取消的订单
//1新订单，等待买家付款
//2买家已付款，等待卖家发货
//3卖家已发货，等待买家确认收货
//4订单完成
//5退货中的订单
//6退货成功;

class order
{
	var $db;
	var $tpl;
	var $page;
	
	function order()
	{
		global $db;
		
		global $tpl;		
		$this -> db     = & $db;
		$this -> tpl    = & $tpl;
	}
	
	function get_order_pro($order_id)
	{
		global $buid;
		$sql="select * from ".ORPRO." where order_id='$order_id'";
		$this->db->query($sql);
		$re=$this->db->getRows();
		return $re;
	}
	
	function buyorder($status='')
	{
		global $buid;
		if(is_numeric($status))
		    $sql="select a.*,b.company from ".ORDER." a left join ".USER." b on a.seller_id=b.userid 
				where a.userid=".$buid." and seller_id!='' and a.status=".$status." order by a.id desc";
		else
            $sql="select a.*,b.company,b.user from ".ORDER." a left join ".USER." b on a.seller_id=b.userid 
				where a.userid=".$buid." and seller_id!='' and a.status>=0 order by a.id desc";
        //=============================
	  	$page = new Page;
		$page->listRows=8;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",8";
		//=====================
		$this->db->query($sql);
		$ore=$this->db->getRows();
		foreach($ore as $k)
		{
			$k['product']=$this->get_order_pro($k['order_id']);
			$k['next_action']=$this->get_next_action('buy',$k['status'],$k['order_id']);
			$k['statu_text']=$this->get_order_statu($k['status']);
			$list[]=$k;
		}
		$re["list"]=$list;
		$re["page"]=$page->prompt();
		$re["process"]=$this->get_order_statu();
		return $re;
	}
	
	function shop_buyorder($status='')
	{
		global $buid;
		if(is_numeric($status))
		    $sql="select a.*,b.company from ".ORDER." a left join ".USER." b on a.seller_id=b.userid 
				where a.userid=".$buid." and seller_id='0' and a.status=".$status." order by a.id desc";
		else
            $sql="select a.*,b.company from ".ORDER." a left join ".USER." b on a.seller_id=b.userid 
				where a.userid=".$buid." and seller_id='0' and a.status>=0 order by a.id desc";
        //=============================
	  	$page = new Page;
		$page->listRows=8;
		if (!$page->__get('totalRows')){
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",8";
		//=====================
		$this->db->query($sql);
		$ore=$this->db->getRows();
		foreach($ore as $k)
		{
			$k['product']=$this->get_order_pro($k['order_id']);
			$k['statu_text']=$this->get_shop_order_statu($k['status']);
			$list[]=$k;
		}
		
		$re["list"]=$list;
		$re["page"]=$page->prompt();
		$re["process"]=$this->get_shop_order_statu();
		return $re;
	}
	function sellorder($status='')
	{
		global $buid;
		if(is_numeric($status))
		    $sql="select a.*,b.company,b.user from ".ORDER." a left join ".USER." b on a.buyer_id=b.userid 
				where a.userid=".$buid." and buyer_id!='' and a.status=".$status." order by a.id desc";
		else
            $sql="select a.*,b.company,b.user from ".ORDER." a left join ".USER." b on a.buyer_id=b.userid 
				where a.userid=".$buid." and buyer_id!='' and a.status>=0 order by a.id desc";
        //=============================
	  	$page = new Page;
		$page->listRows=8;
		if (!$page->__get('totalRows'))
		{
			$this->db->query($sql);
			$page->totalRows = $this->db->num_rows();
		}
        $sql .= "  limit ".$page->firstRow.",8";
		//=============================
		$this->db->query($sql);
		$ore=$this->db->getRows();
		foreach($ore as $k)
		{
			$k['product']=$this->get_order_pro($k['order_id']);
			$k['next_action']=$this->get_next_action('sell',$k['status'],$k['order_id']);
			$k['statu_text']=$this->get_order_statu($k['status']);
			$re["list"][]=$k;
		}
		$re["page"]=$page->prompt();
		$re["process"]=$this->get_order_statu();
		return $re;
	}
	function del_order($deid)
	{
		global $buid;
		
		$sql="update ".ORDER." set status='-1' where order_id='$deid' and userid='$buid'";
		$this->db->query($sql);//删除指定的订单
		
		/*
		$sql="delete from ".ORDER." where order_id='$deid' and userid='$buid'";
		$this->db->query($sql);//删除指定的订单
		
		$sql="select order_id from ".ORDER." where order_id='$deid'";
		$this->db->query($sql);
		$oid=$this->db->fetchField('order_id');
		if(!$oid)
		{	//当买家和卖家的订单全删除之后才删除订单的产品。
			$sql="delete from ".ORPRO." where order_id='$deid'";
			$this->db->query($sql);
		}
		*/
	}
	
	function orderdetail($id)
	{
		global $buid;
		$sql="select * from ".ORDER." where order_id='$id' and userid='$buid'";
		$this->db->query($sql);
        $re=$this->db->fetchRow();
		$re['product']=$this->get_order_pro($id);
		if($re['time_expand'])
		{
			$re['remainder']=$re['uptime']+17*24*60*60-time();
		}
		else
		{
			$re['remainder']=$re['uptime']+10*24*60*60-time();
		}
		$re['statu_text']=$this->get_order_statu($re['status']);
		$process=$this->get_order_statu();unset($process[0]);unset($process[5]);unset($process[6]);
		$re['process']=implode('->',$process);
		
		if($re['seller_id'])
		{
			$sql="select * from ".USER." where userid='$re[seller_id]'";
			$this->db->query($sql);
			$re['sellerinfo']=$this->db->fetchRow();
			$type='buy';
		}
		if($re['buyer_id'])
		{
			$sql="select * from ".ALLUSER." where userid='$re[buyer_id]'";
			$this->db->query($sql);
			$re['buyerinfo']=$this->db->fetchRow();
			$type='sell';
		}
		$re['next_action']=$this->get_next_action($type,$re['status'],$id);
		//---------订单状态整理
		if($re['remainder']<=0)
		{
			if($re['status']==1||$re['status']==2)
				$this->set_order_statu($id,0);
			if($re['status']==3)
				$this->set_order_statu($id,4);
		}
		//----------
		return $re;
		
	}
	
	function shop_orderdetail($id)
	{
		global $buid;
		$sql="select * from ".ORDER." where order_id='$id' and userid='$buid'";
		$this->db->query($sql);
        $re=$this->db->fetchRow();
		$re['product']=$this->get_order_pro($id);
		$re['remainder']=$re['uptime']+10*24*60*60-time();
		$re['statu_text']=$this->get_shop_order_statu($re['status']);
		$re['invoices']=$this->get_invoice($re['invoice']);
		$re['logistics1']=$this->get_logistics($re['logistics']);
		$process=$this->get_shop_order_statu();unset($process[0]);unset($process[5]);unset($process[6]);
		$re['process']=implode('->',$process);
		return $re;
		
	}
	
	function get_invoice($id)
	{
		global $buid;
		$sql="select * from ".INVOICE." where id='$id'";
		$this->db->query($sql);
        $re=$this->db->fetchRow();
		return $re;
	}
	
	function get_logistics($id)
	{
		global $buid;
		$sql="select * from ".DELIVERY." where id='$id'";
		$this->db->query($sql);
        $re=$this->db->fetchRow();
		return $re;
	}
	
	function get_order_statu($statu=NULL)
	{	
		global $config;
		include($config['webroot']."/lang/".$config['language']."/company_type_config.php");
		if($statu!='')
			return  $order_status[$statu];
		else
			return  $order_status;
	}
	
	function get_shop_order_statu($statu=NULL)
	{	
		global $config;
		include($config['webroot']."/lang/".$config['language']."/company_type_config.php");
		if($statu!='')
			return  $order_shop_status[$statu];
		else
			return  $order_shop_status;
	}

	function get_next_action($type,$statu,$oid,$admin='0')
	{
		// $order_action=array('取消','现在付款','发货','确认收货');
		if($statu<5&&$statu>0)
		{
			global $config; 
			if($type=='buy')
			{
				if($statu==1)
					$index=1;//付款
				if($statu==2)
					$index=0;//取消
				if($statu==3)
					$index=3;//收货
				$action='admin_buyorder';
			}
			else
			{
				if($statu==2)
					$index=2;//'发货'
				$action='admin_sellorder';
			}
			if(isset($index))
			{
				if($index>0)
					$flag=$index+1;
				else
					$flag=$index;
				include($config['webroot']."/lang/".$config['language']."/company_type_config.php");
				
				if($admin)
					$str="<a class='buttons' href='module.php?m=product&s=order.php&flag=$flag&id=$oid'>$order_action[$index]</a>";
				else
					$str="<a class='buttons' href='main.php?m=product&s=$action&flag=$flag&id=$oid&status=$_GET[status]'>$order_action[$index]</a>";
				
				if($index==1)
				{
					$str="<a class='buttons' href='main.php?m=payment&s=admin_pay&order_id=$oid'>$order_action[$index]</a>&nbsp;";
					$str.="<a class='buttons' href='main.php?m=product&s=$action&flag=0&id=$oid&status=$_GET[status]'>$order_action[0]</a>";
				}
				if($index==2)
				{
					$str="<a class='buttons' href='main.php?m=product&s=admin_deliver&status=send&id=$oid'>$order_action[$index]</a>";
				}
				return $str;
				
			}
		}
	}
	
	function update_price($price,$oid)
	{	
		global $buid;global $config;
		//修改订单价格，需要请求支付中心
		if(!empty($price))
		{
			//------------改价
			$sql="select buyer_id from ".ORDER." where order_id='$oid' and userid='$buid' and seller_id=0";
			$this->db->query($sql);
			$post['action']='reprice';
			$post['buyer_email']=$this->db->fetchField('buyer_id');
			$post['seller_email']=$buid;//卖家账号
			$post['order_id']=$oid;//外部订单号
			$post['price']=$price;//修改的价格
			$res=pay_get_url($post,true);
			//-------------应用支付结果
			if(!empty($res))
			{
				$res=json_decode($res);
				if($res['statu']=='true'&&$res['auth']!=md5($config['authkey']))
				{
					$sql="update ".ORDER." set product_price=product_price+'$price' where order_id='$oid'";
					$this->db->query($sql);
				}
			}
		}
	}
	
	function set_order_statu($oid="",$status="")
	{
		global $buid,$config;
	
		if($status==0)
		{
			$sql="select seller_id from ".ORDER." where order_id='$oid' and userid='$buid'";
			$this->db->query($sql);
			$post['action']='update';
			$post['seller_email']=$this->db->fetchField('seller_id');
			$post['buyer_email']=$buid;//卖家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=0;//取消的订单
			$res=pay_get_url($post,true);
	
		}
		if($status==3)
		{
			$sql="select buyer_id from ".ORDER." where order_id='$oid' and userid='$buid' and buyer_id!=''";
			$this->db->query($sql);
			//===========发货
			$post['action']='update';
			$post['buyer_email']=$this->db->fetchField('buyer_id');
			$post['seller_email']=$buid;//买家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=3;//
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if($status==4)
		{	
			//===========成功，反回结果给支付中心。
			$sql="select seller_id from ".ORDER." where order_id='$oid' and userid='$buid'";
			$this->db->query($sql);
		
			$post['action']='update';
			$post['seller_email']=$this->db->fetchField('seller_id');
			$post['buyer_email']=$buid;
			$post['order_id']=$oid;//外部订单号
			$post['statu']=4;
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if($status==5)
		{
			//提交退货审请
			$sql="select seller_id from ".ORDER." where order_id='$oid' and userid='$buid'";
			$this->db->query($sql);
			
			$post['action']='update';
			$post['seller_email']=$this->db->fetchField('seller_id');
			$post['buyer_email']=$buid;//卖家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=5;
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if($status==6)
		{	
			//退款，由买家发起，管理员进行退款操作。
			$sql="select userid,seller_id from ".ORDER." where order_id='$oid' and seller_id!=''";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			
			$post['action']='update';
			$post['seller_email']=$re['seller_id'];//卖家账号
			$post['buyer_email']=$re['userid'];//买家账号
			$post['order_id']=$oid;//外部订单号
			$post['statu']=6;
			$res=pay_get_url($post,true);//跳转至订单生成页面
		}
		if(!empty($res))
		{
			$res=json_decode($res);
			if($res->statu=='true'&&$res->auth!=md5($config['authkey']))
			{
				//------------如果结果正常就对订单进行取消操作。
				$sql="update ".ORDER." set status='$status',uptime=".time()." where order_id='$oid'";
				$this->db->query($sql);
				//------------提取佣金
				if($status==4)
				{
					$this->add_commission($oid,$post['seller_email']);
				}
				
			}
			return true;
		}
		else
			return false;
	}
	
	//===========佣金计算。
	function add_commission($order_id,$seller)
	{
		global $config;
		$re=$this->get_order_pro($order_id);
		foreach($re as $v)
		{
			$sql="select commission from ".PCAT." where catid='$v[pcatid]'";
			$this->db->query($sql);
			$cmi=$this->db->fetchField('commission');
			$one_price+=$v['price']*$cmi;
		}
		
		if($one_price>0)
		{
			//--------------写入流水账。卖家扣相关的费用=总站佣金+分站佣金。
			include("$config[webroot]/module/payment/lang/$config[language].php");
			$post['type']=1;//直接到账
			$post['action']='add';//
			$post['buyer_email']=$seller;//
			$post['seller_email']='admin@systerm.com';//
			$post['order_id']=time();//外部订单号
			$post['extra_param']='Commission';
			$post['price']=$one_price;//订单总价，单价元
			$post['note']=$note[12].$order_id;
			pay_get_url($post,true);//跳转至订单生成页面
			//--------------
		}
		
	}
	
	function get_addr()
	{	
		global $buid;
		$sql="select * from  ".SHIPPINGADDR." where `userid`='$buid'";
		$this->db->query($sql);
		return $this->db->getRows($sql);
	}
	
	function get_return_addr($uid)
	{	
		$sql="select * from  ".SHIPPINGADDR." where `userid`='$uid' and `default_delivery`='1'";
		$this->db->query($sql);
		return $this->db->fetchRow($sql);
	}
	
	function get_fastmail()
	{	
		$sql="select * from ".FASTMAIL."  order by id";
		$this->db->query($sql);
	    return $this->db->getRows();
	}
	
	function updateorder()
	{
		$sql="update ".ORDER." set 
				deliver_id='$_POST[deliver_id]',deliver_code='$_POST[deliver_code]',deliver_name='$_POST[deliver_name]',
				deliver_addr_id='$_POST[deliver_addr_id]',deliver_time=".time()."
		 	where order_id='$_POST[id]'";
		$this->db->query($sql);
		
		//----------------------- 更新订单状态
		$this->set_order_statu($_POST['id'],3);
	}
	
	
	function add_return()
	{
		global $buid;
		$sql="select user from ".ALLUSER." where userid='$buid'";
		$this->db->query($sql);
		$user=$this->db->fetchField('user');
		
		$sql="select * from  ".SHIPPINGADDR." where `userid`='$_POST[seller_id]' and `default_delivery`='1'";
		$this->db->query($sql);
		$addr=$this->db->fetchRow($sql);
		
		$sql="select id from ".PRORETURN." where oid ='$_POST[id]'";
		$this->db->query($sql);
		$id=$this->db->fetchField('id');
		
		if($id>0)
		{
			$sql="update ".PRORETURN." set statu='2',add_time='".time()."',message='$_POST[message]' where oid='$_POST[id]'";
			$this->db->query($sql);	
		}
		else
		{
			$sql="insert into ".PRORETURN." (oid,return_code,seller_id,seller_name,buyer_id,buyer_name,add_time,message,return_addr_id,return_addr_name,return_addr,return_post,statu) values ('$_POST[id]','R".time()."','$_POST[seller_id]','$_POST[seller_name]','$buid','$user','".time()."','$_POST[message]','$addr[id]','$addr[name]','$addr[province] $addr[city] $addr[addr]','$addr[post]','0')";
			$this->db->query($sql);
			$rid=$this->db->lastid();
		
			foreach($_POST['pid'] as $val)
			{
				$sql="select name,price,pic,num from ".ORPRO." where pid='$val'";
				$this->db->query($sql);
				$pro=$this->db->fetchRow();
				
				$sql="insert into ".PRORETURNG." (oid,rid,pid,pname,price,returnnum,pic) values ('$_POST[id]','$rid','$val','$pro[name]','$pro[price]','$pro[num]','$pro[pic]')";
				$this->db->query($sql);
				
			}
		}
		//----------------------- 更新订单状态至退货状态
		$this->set_order_statu($_POST['id'],5);
		//-----------------------
	}
	
	function get_return()
	{
		global $buid;
		$sql="select * from ".PRORETURN." where oid ='$_GET[id]'";
		$this->db->query($sql);
		return $this->db->fetchRow();
	}
	
	function get_return_goods($id)
	{
		$sql="select * from ".PRORETURNG." where rid ='$id'";
		$this->db->query($sql);
		$de=$this->db->getRows();
		return $de;
	}
	
	function update_return($type)
	{
		if($type=='agree')
		{
			
			$sql="update ".PRORETURN." set statu='9' where oid='$_GET[oid]'";
			$this->db->query($sql);	
			
			$this->set_order_statu($_GET['oid'],6);//同意退货。
		}
		else
		{
			$sql="update ".PRORETURN." set statu='1' where oid='$_GET[oid]'";
			$this->db->query($sql);	
		}
	}
	
	
	function add_talk()
	{
		global $buid;	
		
		$sql="select seller_id,seller_name,buyer_id,buyer_name from ".PRORETURN." where oid ='$_POST[oid]'";
		$this->db->query($sql);
		$de=$this->db->fetchRow();
		if($buid==$de['seller_id'])
		{
			$uname=$de['seller_name'];
			$utype='1';
		}
		if($buid==$de['buyer_id'])
		{
			$uname=$de['buyer_name'];
			$utype='2';
		}
		$sql="insert into ".TALK." (oid,uid,uname,utype,content,add_time) values ('$_POST[oid]','$buid','$uname','$utype','$_POST[msg]','".time()."')";
		$this->db->query($sql);
	}
	
	function get_talk()
	{
		$sql="select * from ".TALK." where oid ='$_GET[id]' order by add_time desc ";
		$this->db->query($sql);
		$de=$this->db->getRows();
		return $de;
	}
}
?>
