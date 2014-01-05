<?php
session_start();
include_once("includes/global.php");
include_once("includes/admin_global.php");
include_once("includes/admin_class.php");
include_once("includes/insert_function.php");
//===============================================
$action=isset($_GET['action'])?$_GET['action']:"main";
$submit=isset($_POST['submit'])?$_POST['submit']:NULL;
$deid=isset($_GET['deid'])?$_GET['deid']:NULL;
$admin = new admin();
//---------------------清缓存
if(!empty($_POST)||!empty($_GET['deid'])||!empty($_GET['rec']))
	$admin->clear_user_shop_cache();
	
//---------------------登录检查,个人或企业会员
$admin->is_login($action);
$is_company=$admin->check_myshop();
// if(empty($_SESSION['USER_TYPE']))
$_SESSION['USER_TYPE']=$is_company;
if($_GET['cg_u_type'])
	$_SESSION['USER_TYPE']=$_GET['cg_u_type']*1;

$tpl->assign("cg_u_type",$_SESSION['USER_TYPE']);

//-------店铺信息不存在，但是却进入的是卖家的后台，需要申请开店
if($is_company==1&&$_SESSION['USER_TYPE']==2&&$_GET['s']!='admin_step'&&$_GET['action']!='msg')
	header("Location:main.php");

//--------------------更换语言包
if(!empty($_GET['lang']))
{
	$_SESSION['USER_LANG']=$_GET['lang'];
	$admin->msg("main.php");
}
if(empty($_SESSION['USER_LANG']))
{
	if($_SESSION['USER_TYPE']==2)
		$_SESSION['USER_LANG']='cn';
	else
		$_SESSION['USER_LANG']=$config['language'];
}
include("lang/".$_SESSION['USER_LANG']."/user_admin.php");

//-----------------------用户菜单加载
if($_SESSION['USER_TYPE']==1)
	include("lang/".$_SESSION['USER_LANG']."/admin_menu.inc_p.php");
else
	include("lang/".$_SESSION['USER_LANG']."/admin_menu.inc.php");

//店铺是否开启
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
$shop_statu=$shop->GetShopStatus($buid);

switch ($action){
	case "admin_subscribe":
	{	
		include_once("includes/plugin_tradealter_class.php");
		$tradealter=new tradealter();
		if(!empty($_POST['addid']))
		{
			if(!empty($_POST['seditid']))
				$re=$tradealter->up_subscribe($_POST['seditid']);
			else
				$re=$tradealter->up_subscribe();
			if($re)
				msg("main.php?action=admin_subscribe");
		}
		if(!empty($_GET['editid']))
			$tpl->assign("de",$tradealter->list_subscribe($_GET['editid']));
		if(!empty($_GET['delid']))
			$tradealter->delete_subscribe($_GET['delid']);
		$tpl->assign("subscribe",$tradealter->list_subscribe());
		$page="admin_subscribe.htm";
		break;
	}
	case "logout":
	{
		global $config;
		include_once("$config[webroot]/config/reg_config.php");
		$config = array_merge($config,$reg_config);
		bsetcookie("USERID",NULL,time() - 7200 * 20,"/");
		setcookie("USER",'aaaaaaaa',time() - 7200 * 200,"/");
		//=====================
		if($config['openbbs']==2)
		{
			include_once("$config[webroot]/uc_client/client.php");
			echo uc_user_synlogout();
		}
		$_SESSION['USER_TYPE']=NULL;
		msg("$config[weburl]/index.php");
		break;
	}
	case "msg":
	{
		$tpl->assign("lang",$lang);
		$tpl->assign("config",$config);
		include_once("footer.php");
		$output=tplfetch("admin_msg.htm",$flag,true);
		break;
	}
	default:
	{
		if(!empty($_GET['m']))
		{
			$s=$_GET['s'];
			if(!$shop_statu and ($s=="admin_product" || $s=="admin_product_list" || $s=="admin_product_storage" || $s=="admin_product_batch"))
			{
				$admin->msg('main.php','shop_statu','failure');
			}
			else
			{
				if(file_exists($config['webroot'].'/config/module_'.$_GET['m'].'_config.php'))
				{
					@include($config['webroot'].'/config/module_'.$_GET['m'].'_config.php');
					$mcon='module_'.$_GET['m'].'_config';
					@$config = array_merge($config,$$mcon);
				}
				if(file_exists("$config[webroot]/module/".$_GET['m']."/lang/".$_SESSION['USER_LANG'].".php"))
					include("$config[webroot]/module/".$_GET['m']."/lang/".$_SESSION['USER_LANG'].".php");		//#调用模块语言包
				include("module/".$_GET['m']."/".$_GET['s'].".php");
				$tpl->template_dir=$config['webroot']."/templates/".$config['temp']."/";
			}
			break;
		}
		else
		{
			//-------------------------------------提醒开通支付账号
			$sql = "select pay_uid,cash from ".PUSER." where userid='$buid'";
			$db->query($sql);
			$pay_re=$db->fetchRow();
			$tpl->assign("cash",$pay_re['cash']);
			
			if(empty($pay_re['pay_uid']))
				$admin->msg("main.php?m=payment&s=admin_info","请先开通支付账号",'failure');
			//------------------------------------
			include_once("module/shop/includes/plugin_shop_class.php");
			$shop=new shop();	
			$tpl->assign("uvlist",$admin->who_view_myshop($buid));//谁访问过我
			//-------------------------------------------
			if($_SESSION['USER_TYPE']==1)
			{	
				$cominfo=$shop->get_shop_info($buid);
				$admin->tpl->assign("cominfo",$cominfo);
				$count['order']=$shop->get_all_count(ORDER,array('1','3','4'),'2');
				$admin->tpl->assign("shop_count",$count);
	
				include_once("module/message/includes/plugin_msg_class.php");
				$msg=new msg();
				$tpl->assign("mailNum",$msg->getMailNum());
				
				include_once("module/sns/includes/plugin_friend_class.php");
				$friend=new friend();
				$tpl->assign("friend",$friend->GetFriend($buid));
				
				$page="admin_main_p.htm";
			}
			else
			{
				$cominfo=$shop->get_shop_info($buid);
				$admin->tpl->assign("cominfo",$cominfo);
				//---------------------------------
				//获取当前用户店铺动态评分
				$admin->tpl->assign("shop_comment",$shop->get_shop_comment());
				//获取当前用户产品 评论 订单 数量
				$count['prdouct']=$shop->get_all_count(PRO,array('-1','-2','1','2'));
				$count['pro_comment']=$shop->get_all_count(PCOMMENT,'');
				$count['order']=$shop->get_all_count(ORDER,array('all','1','2','3','5','4'));
				$admin->tpl->assign("shop_count",$count);
				
				$page="admin_main.htm";
			}
			//------------------------------------------------
			break;
		}
	}
}

$tpl->assign("lang",$lang);
include_once("footer.php");
if(!empty($nohead))
	$tpl->display($page);
else
{
	if(!empty($output))
		$tpl->assign("output",$output);
	else
		$tpl->assign("output",$admin -> tpl -> fetch($page));
	
	if($_SESSION['USER_TYPE']==1)
	{
		include_once("module/shop/includes/plugin_shop_class.php");
		$shop=new shop();	
		$count['order']=$shop->get_all_count(ORDER,array('1','3','4'),'2');
		$admin->tpl->assign("shop_count",$count);
		$page="admin_inc_p.htm";
	}
	else
	{
		$tpl->assign("shop_statu",$shop_statu);
		$page="admin_inc.htm";
	}
	$tpl->display($page);
}
?>
