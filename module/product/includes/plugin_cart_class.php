<?php

	function get_log_price($area,$pde,$num)
	{
		if(empty($pde['freight_type']))
		{	
			//卖家承担运费
			$mail=0;
			$ems=0;
			$express=0;
		}
		else
		{
			if(empty($pde['freight']))
			{	//非运费模板
				$mail=$pde['post_price']*$num;
				$ems=$pde['ems_price']*$num;
				$express=$pde['express_price']*$num;
			}
			else
			{	//运费模板
				global $db;
				$city=$area;
				$lgid=$pde['freight'];
				$sql="select price_type from ".LGSTEMP." where id='$lgid'";
				$db->query($sql);
				$price_type=$db->fetchField('price_type');
				if($price_type=='件')
				{
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='mail'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='mail'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num<=$re['default_num'])
						$mail=$re['default_price'];
					else
						$mail=$re['default_price']+ceil(($num-$re['default_num'])/$re['add_num'])*$re['add_price'];
					
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='ems'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='ems'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num<=$re['default_num'])
						$ems=$re['default_price'];
					else
						$ems=$re['default_price']+ceil(($num-$re['default_num'])/$re['add_num'])*$re['add_price'];
					
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='express'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='express'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num<=$re['default_num'])
						$express=$re['default_price'];
					else
						$express=$re['default_price']+ceil(($num-$re['default_num'])/$re['add_num'])*$re['add_price'];
				}
				elseif($price_type=='kg')
				{
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='mail'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='mail'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['weight']<=$re['default_num'])
						$mail=$re['default_price'];
					else
						$mail=$re['default_price']+ceil(($num*$pde['weight']-$re['default_num'])/$re['add_num'])*$re['add_price'];
					
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='ems'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='ems'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['weight']<=$re['default_num'])
						$ems=$re['default_price'];
					else
						$ems=$re['default_price']+ceil(($num*$pde['weight']-$re['default_num'])/$re['add_num'])*$re['add_price'];
					
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='express'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='express'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['weight']<=$re['default_num'])
						$express=$re['default_price'];
					else
						$express=$re['default_price']+ceil(($num*$pde['weight']-$re['default_num'])/$re['add_num'])*$re['add_price'];
				}
				elseif($price_type=='m³')
				{
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='mail'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='mail'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['cubage']<=$re['default_num'])
						$mail=$re['default_price'];
					else
						$mail=$re['default_price']+ceil(($num*$pde['cubage']-$re['default_num'])/$re['add_num'])*$re['add_price'];
						
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='ems'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='ems'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['cubage']<=$re['default_num'])
						$ems=$re['default_price'];
					else
						$ems=$re['default_price']+ceil(($num*$pde['cubage']-$re['default_num'])/$re['add_num'])*$re['add_price'];
					
					$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and define_citys like '%,$city%' and logistics_type='express'";
					$db->query($sql);
					$re=$db->fetchRow();
					if(empty($re['id']))
					{	//没有为城市定价
						$sql="select * from ".LGSTEMPCON." where temp_id='$lgid' and logistics_type='express'";
						
						$db->query($sql);
						$re=$db->fetchRow();
					}
					if($num*$pde['cubage']<=$re['default_num'])
						$express=$re['default_price'];
					else
						$express=$re['default_price']+ceil(($num*$pde['cubage']-$re['default_num'])/$re['add_num'])*$re['add_price'];
				}

			}
		}
		$re['mail']=$mail;
		$re['ems']=$ems;
		$re['express']=$express;
		return $re;
		
	}
//================================================
class cart
{
	var $db;
	
	function cart()
	{
		global $db;	
		$this -> db     = & $db;
	
	}
	//获取购物车单个商品信息
	function get_cart_detail($id=NULL)
	{
		global $buid;
		$sql="select * from ".CART." where id=$id";
		$this->db->query($sql);
		$re=$this->db->fetchRow();	
		return $re;
	}
	//获取一个产品的物流价格

	//获取一个卖家的已放入购物车的商品信息
	function get_prolist($sell_userid=NULL,$area)
	{
		global $buid;
		
		$sql="select 
		a.*,a.price*a.num as sumprice,a.price,a.num,
		b.amount,b.catid,b.pname,b.pic,b.id as pid,b.freight,b.freight_type,b.post_price,b.ems_price,b.express_price,b.point,
		c.setmeal as setmealname,c.stock from 
		".CART." a left join 
		".PRO." b on  a.pid=b.id left join 
		".SETMEAL." c on a.setmeal=c.id 
		where a.sell_userid=$sell_userid and a.userid=$buid";
		$this->db->query($sql);	 	
		$re=$this->db->getRows();	
	
		foreach($re as $key=>$val)
		{   
			if(empty($val['amount']))
				$re[$key]['amount']=$val['stock'];//产品库存数量,用套餐的替换
						
			$sumprice+=$val['sumprice'];//单店总价
			$fprice=get_log_price($area,$val,$val['num']);
			$list['mail']+=$fprice['mail'];
			$list['ems']+=$fprice['ems'];
			$list['express']+=$fprice['express'];
		}
		$list['sumprice']=$sumprice;//单个卖家的商品总价
		$list['prolist']=$re;//单个店铺的产品列表
		return $list;
	}
	
	//获取购物车卖家列表及卖家商品列表信息及总商品价格
	function get_listcart($area)
	{
		global $buid;  
		$sumprice=0;			
		$sql="select a.id,a.sell_userid,a.setmeal,b.company,b.logo,b.tel  from ".CART." a left join    
		".USER." b on a.sell_userid=b.userid where a.userid=$buid  group by sell_userid";
		
		$this->db->query($sql);
		$re=$this->db->getRows();
		foreach($re as $key=>$v)
		{	
		     //保存单个店铺商品总价 //平邮 //快点 //EMS 总邮费
			$pro=$this->get_prolist($v['sell_userid'],$area);
			$re[$key]['sumprice']=$pro['sumprice'];
			$re[$key]['mail']=$pro['mail'];
			$re[$key]['ems']=$pro['ems'];
			$re[$key]['express']=$pro['express'];
			
			$re[$key]['prolist']=$pro['prolist'];
			$sumprice+=$pro['sumprice'];	
			
		}
		$res['cart']=$re;
		$res['sumprice']=$sumprice;
		return $res;
	}
	//增加商品
	function add_cart($prid=NULL,$num=1,$sid=0)
	{
		global $buid;  
		$num*=1;
		$sql="select userid,price,amount from ".PRO." where id=$prid";
		$this->db->query($sql);
		$pro=$this->db->fetchRow(); 
		
		if($sid)
		{
			$sql="select * from ".SETMEAL." where id=$sid";
			$this->db->query($sql);
			$de=$this->db->fetchRow();
		}
		$de['stock']=$de['stock']?$de['stock']:"0";
		
		if($pro['amount']<=0 and $sid==0)//数量为0或商品不存在
		{
			return 'error';
		}
		elseif($de['stock']<=0 and $sid)
		{
			return 'error';
		}
		else{	
		
			$sql="select a.id,b.amount,a.num from ".CART." a left join ".PRO." b on  a.pid=b.id   where pid=$prid and a.userid=$buid limit 1";
			$this->db->query($sql);
			$re=$this->db->fetchRow();
			$rnum=empty($re['num'])?0:$re['num'];
		
			if($rnum+$num>$pro['amount'] and $sid==0)
			{//库存不够	
			    if(!empty($re['id']))//如果购物车有该商品则把数量设为最大值
				{
					$sql="update ".CART." set num=$re[amount] where id=$re[id]";	
					$this->db->query($sql);	
				}
				else//如果不存在该商品则放入最大数量
				{
					$price=$sid?$de['price']:$pro['price'];
					$sql="insert into ".CART."(`userid` ,`pid` ,`sell_userid` ,`price` ,`num` ,`time`,`setmeal` )
				VALUES ('$buid', '$prid', '$pro[userid]', '$price','$re[amount]', ".time().",$sid)"; 	
					$this->db->query($sql);	
				}
				return 'error1';
			}
			elseif($rnum+$num>$de['stock'] and $sid)
			{//库存不够	
			    if(!empty($re['id']))//如果购物车有该商品则把数量设为最大值
				{
					$sql="update ".CART." set num=$re[amount] where id=$re[id]";	
					$this->db->query($sql);	
				}
				else//如果不存在该商品则放入最大数量
				{
					$price=$sid?$de['price']:$pro['price'];
					$sql="insert into ".CART."(`userid` ,`pid` ,`sell_userid` ,`price` ,`num` ,`time` ,`setmeal`)
				VALUES ('$buid', '$prid', '$pro[userid]', '$price','$re[amount]', ".time().",$sid)"; 	
					$this->db->query($sql);	
				}
				return 'error1';
			}
			else
			{		
				if(!empty($re['id'])||empty($num))
					return false;	//如果商品已经存在 防止重复提交
				$price=$sid?$de['price']:$pro['price'];
				$sql="insert into ".CART."(`userid` ,`pid` ,`sell_userid` ,`price` ,`num` ,`time` ,`setmeal` )
				VALUES ('$buid', '$prid', '$pro[userid]', '$price',$num, ".time().",$sid)"; 	  
			
				$this->db->query($sql);	
			}
		}
	}
	//删除购物车内容
	function del_cart($id=NULL)
	{
		if(is_array($id))
		{
			$id=implode(',',$id);
			$sql="delete from ".CART." where id in ($id)";
		}
		else
		{
			$id*=1;
			$sql="delete from ".CART." where id='$id'";
		}
		$flag=$this->db->query($sql);	   
		return $flag;
	}
	//清空购物车内容
	function clear_cart(){
		global $buid;  
		$sql="delete from ".CART." where userid='$buid'";
		$flag=$this->db->query($sql);	   
		return $flag;
	}
	
	//编辑购物车数量
	function edit_cart($cartid=NULL,$num=NULL)
	{
		global $buid;  
		if($num<1&&!empty($cartid))//如果数量小于1就删除
			$this->del_cart($cartid);
		$sql="select b.amount,a.num,c.stock,a.setmeal from ".CART." a left join 
			".PRO." b on a.pid=b.id left join ".SETMEAL." c on a.setmeal=c.id where a.id=$cartid and a.userid=$buid";
		$this->db->query($sql);
		$re=$this->db->fetchRow();
		if($re['setmeal'])
		{
			if($num>$re['stock']&&isset($re['stock']))
			{
				$sql="update ".CART." set num='$re[stock]' where id=$cartid";	
				$this->db->query($sql);			
				return 'error';//库存不够	
			}
			else
			{  
				$sql="update ".CART." set num='$num' where id='$cartid'";
				$flag=$this->db->query($sql);	   
				return false;
			}
		}
		else
		{
			if($num>$re['amount']&&isset($re['amount']))
			{
				$sql="update ".CART." set num='$re[amount]' where id=$cartid";	
				$this->db->query($sql);			
				return 'error';//库存不够	
			}
			else
			{  
				$sql="update ".CART." set num='$num' where id='$cartid'";
				$flag=$this->db->query($sql);	   
				return false;
			}
		}
	}

}
?>
