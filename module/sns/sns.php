<?php

	include_once("$config[webroot]/module/sns/includes/plugin_sns_class.php");
	$sns=new sns();
	if($_GET['curpage'])
	{
		echo "<div id=module>".$sns->get_sns()."</div>";die;
	}
	
	if(!empty($_POST['bid'])&&is_numeric($_POST['bid'])&&$_POST['op']=='del')
	{
		$sns->del_sns($_POST['bid']);
	}
	if(!empty($_POST['act']))
	{
		$sns->add_sns($_POST['act']);
	}
	
	if($_GET['op']=="sharegoods")
	{
		include_once("$config[webroot]/module/sns/includes/plugin_share_class.php");
		$share=new share();
		if($_GET['pid'])
		{
			$tpl->assign("de",$share->GetProduct($_GET['pid']));	
		}
		else
		{	
			$tpl->assign("re",$share->GetShareGoods());
		}
		
		$page="admin_sharegoods.htm";
	}
	
	if($_GET['op']=="shareshop")
	{
		include_once("$config[webroot]/module/sns/includes/plugin_share_class.php");
		$share=new share();
		$tpl->assign("re",$share->GetShareShop());
		$page="admin_shareshop.htm";
	}
	
	if($_GET['op']=="forward")
	{
		$page="admin_forward.htm";
	}
	
	if(!empty($page))
	{
		$tpl->assign("config",$config);
		$tpl->assign("lang",$lang);
		$output=tplfetch($page,$flag,true);
	}
?>