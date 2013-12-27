<?php
	
	include_once("includes/global.php");
	include_once("includes/smarty_config.php");
	include_once("includes/plugin_home_class.php");
	include_once("$config[webroot]/module/sns/includes/plugin_sns_class.php");
	//===============================================
	
	$action=!empty($_GET['act'])?$_GET['act']:NULL;
	$uid=!empty($_GET['uid'])?$_GET['uid']:NULL;
	
	$home = new home();
	$sns = new sns();
	
	$tpl -> template_dir = $config['webroot'] . "/templates/default/home/";
	$tpl -> compile_dir = $config['webroot'] . "/templates_c/default/home/";
	
	$tpl->assign("member",$home->Member($uid));
	$tpl->assign("count",$home->AllCount($uid));
	$tpl->assign("userid",$userid=$home->UserId());
	$tpl->assign("friend",$home->Friend($userid,$uid));
	$tpl->assign("fan",$home->Fan($userid,$uid));
	switch ($action)
	{
		case "product":
		{	
			//分享的宝贝
			$tpl->assign("sharegoods",$home->ShareGoodsList($uid));
			$page="product.htm";
			break;
		}
		case "trace":
		{	
			//新鲜事
			$tpl->assign("blog",$home->Blog($uid,20));
			$page="trace.htm";
			break;
		}
		default:
		{
			if($_POST['submit']=="forward" and !empty($_POST['forwardid']))
			{
				$sns->add_sns('forward');
				msg('main.php');
			}
			//分享的宝贝
			$tpl->assign("sharegoods",$home->ShareGoods($uid));
			//新鲜事
			$tpl->assign("blog",$home->Blog($uid,'8'));
			$page  = "main.htm";
			break;
		}
	}
	
	//访客
	$tpl->assign("visitors",$home->Visitors($uid));
	
	$tpl->assign("title",'看TA怎么淘到好宝贝');
	include_once("footer.php");
	if(empty($output))
	{
		$tpl->assign("output",$tpl->fetch($page));
	}
	else
	{
		$tpl->assign("output",$output);
	}
	$tpl ->display("index.htm");
?>