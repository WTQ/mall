<?php
if(empty($buid)&&empty($_GET['type']))
	msg($config['weburl']."/?m=product&s=confirm_order&type=login");
else
{
	if(!empty($buid))
	{
		include_once("$config[webroot]/module/member/includes/plugin_orderadder_class.php");
		include_once("$config[webroot]/module/product/includes/plugin_cart_class.php");
		$cart=new cart();
		$orderadder=new orderadder();
		//============================读出收货地址
		$tpl->assign("listadder",$adlist=$orderadder->get_orderadderlist());
		//============================读出购物车的数据
		if(empty($_GET['arrid']))
		{
			$on_city=$adlist[0]['city'];
			$re=$adlist[0];
		}
		else
		{
			$adder=empty($_POST['addres'])?$_GET['arrid']:$_POST['addres'];
			$re=$orderadder->get_orderadder($adder*1);
			$on_city=$re['city'];
		}
		$cartlist=$cart->get_listcart($on_city);
		
		//-----------如果为空,返回至购物车
		if(empty($cartlist['sumprice'])&&empty($_GET['type']))
			msg($config['weburl']."/?m=product&s=cart");
			
		//=============================提交订单
		if($_POST['act']=='order'&&empty($_GET['type']))
		{  
			//--------------收件人
			if(empty($_POST['addres']))
				msg($config['weburl']."/?m=product&s=confirm_order&type=clear");
			//----------循环店铺,生成多个订单
			foreach($cartlist['cart'] as $key=>$val)
			{
				$sell_userid=$val['sell_userid'];
				if(!empty($sell_userid))
				{
					$logistics_type=$_POST['logistics_type_'.$sell_userid];//物流方式
					$logistics_price=$_POST['logistics_price_'.$sell_userid];//物流价格
					
					$product_price=$val['sumprice'];//购物总价
					$order_id=date("Ymdhis").rand(0,9);//订单号
					$pname=NULL;//此次购物的产品名总称
					
					/***生成买家订单****/
					$sql = "INSERT INTO ".ORDER." 
					( `userid`,`order_id`,`buyer_id`,`seller_id`,`buyer_name`,`buyer_addr`,`buyer_tel`,`buyer_mobile`,`buyer_zip`,`product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`creat_time`,`uptime`) 
					VALUES 
($buid,$order_id,'0',$sell_userid,'$re[name]','$re[area] $re[address]','$re[tel]','$re[mobile]','$re[zip]','$product_price','$logistics_type','$logistics_price',1,'".$_POST['msg_'.$sell_userid]."','".time()."','".time()."')"; 
					$flag=$db->query($sql);
					
					/***生成卖家订单****/
					$sql = "INSERT INTO ".ORDER." 
					(`userid`,`order_id`,`buyer_id`,`seller_id`,`buyer_name`,`buyer_addr`,`buyer_tel`,`buyer_mobile`,`buyer_zip`,
					  `product_price`,`logistics_type`,`logistics_price`,`status`,`des`,`creat_time`,`uptime`) 
					VALUES 
					($sell_userid,$order_id,'$buid','0','$re[name]','$re[area] $re[address]','$re[tel]','$re[mobile]','$re[zip]',
					'$product_price','$logistics_type','$logistics_price',1,'".$_POST['msg_'.$sell_userid]."','".time()."','".time()."')"; 
					$flag=$db->query($sql);
					
					//-----保存商品信息，两份订单一份产品，当一个订单删除时要看一下有没有同样的订单号存在，如果有就不要清空产品
					foreach($val['prolist'] as $key=>$val)
					{    	
						$sql = "INSERT INTO ".ORPRO." (`order_id`,`buyer_id`,`pid`,`pcatid`,`name`,`pic`,`price`,`num`,`time`,`setmeal`) 
						VALUES 
						($order_id,$buid,$val[pid],'$val[catid]','$val[pname] $val[setmealname]','".$val['pic']."','".$val['price']."','".$val['num']."','".time()."',$val[setmeal])"; 
						$db->query($sql);
						$pname=$pname.'-'.$val['pname'];
					}
					
					include_once("$config[webroot]/module/payment/lang/$config[language].php");
					$post['action']='add';//填加流水
					$post['type']=2;//担保接口
					$post['seller_email']=$sell_userid;//卖家账号
					$post['buyer_email']=$buid;//卖家账号
					$post['order_id']=$order_id;//外部订单号
					$post['price']=$product_price*1+$logistics[1]*1;//订单总价，单价元
					$post['extra_param']='';//自定义参数，可存放任何内容（除=、&等特殊字符外），不会显示在页面上
					$post['return_url']='main.php?m=product&s=admin_orderdetail&id='.$order_id.'&type=buy';//返回地址
					$post['notify_url']='main.php?m=product&s=admin_orderdetail&id='.$order_id.'&type=buy';//异步返回地址。
					$post['note']=$note[3].$pname;
					$re=pay_get_url($post,true);//跳转至订单生成页面
					if($re<0)
					{
						if($re==-2)
							msg('main.php?m=payment&s=admin_info','您的支付账户还没有开通');
						if($re==-1)
							msg("$config[weburl]/?m=product&s=cart",'卖家没有开通支付功能，暂不能购买');	
					}
				}		
			}
			//------------清空购物车
			$cart->clear_cart();
			msg($config['weburl']."/main.php?m=payment&s=admin_pay&order_id=$order_id&cg_u_type=1");//订单提交成功
			die;
		}		
		
		//=====================================小窗口操作 填加及修改收货地址
		//-------------------增加收货地址
		if(!empty($_POST['submit'])&&$_POST['act']=='submit')
		{  
			$flag=$orderadder->add_orderadder(); 
			if($flag==false)
				$tpl->assign("close","3");//不能超过5条
			else if($flag=='error')
				$tpl->assign("close","1");//参数不正确
			else
			{
				$tpl->assign("close","2");//正常关闭
				$tpl->assign("adderid",$flag);// 并选中当前内容
			}
		}
		//-------------------修改收货地址
		if(!empty($_POST['submit'])&&$_POST['act']=='edit')
		{
			$flag=$orderadder->edit_orderadder($_POST['edid']); 
			if($flag)
			{	
				$tpl->assign("close","2");
				$tpl->assign("adderid",$_POST['edid']);// 并选中当前内容
			}
		}
		//-------------------显示收货地址
		if(!empty($_GET['adrid'])&&is_numeric($_GET['adrid']))
			$tpl->assign("de",$orderadder->get_orderadder($_GET['adrid']));	
	}
}

//=================================================
$tpl->assign("config",$config);
if(empty($_GET['showadder']))
{
	$tpl->assign("cart",$cartlist['cart']);
	$tpl->assign("sumprice",$cartlist['sumprice']);
	
	$tpl->assign("current","product");
	include_once("footer.php");
	$out=tplfetch("confirm_order.htm",$flag);	
}
else
{
	$tpl->assign("prov",GetDistrict());
	$out=tplfetch("add_address.htm",NULL,true);
}
?>