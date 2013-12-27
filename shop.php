<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("includes/user_shop_class.php");
//===============================================
$id=$_GET['id']*1;
$catid=$_GET['catid']*1;
$_GET['uid']*=1;
$list_type=empty($_GET['list_type'])?NULL:$_GET['list_type']*1;
$cat=empty($_GET['cat'])?NULL:$_GET['cat'];
$_GET['firstRow']=empty($_GET['firstRow'])?NULL:$_GET['firstRow'];
$action=isset($_GET['action'])?$_GET['action']:NULL;
//------------------------
user_read_rec($buid,$_GET['uid'],3);//记录会员查看店铺
//------------------------
$shop = new shop();
if(isset($_GET['spread'])&&is_numeric($_GET['spread']))
	$shop->site_spread($_GET['spread']);
//------------------------
$flag=md5($action.$_GET['uid'].$id.$_GET['firstRow'].$config['weburl'].$catid.$list_type.$cat);
if($action!="mail"&&$action!="comments"&&empty($_GET['template'])&&$buid!=$_GET['uid'])
{	
	$dir=get_userdir($_GET['uid']);//根据会员ID生成缓存目录
	useCahe('shop/'.$dir,true);
}
if(!$tpl->is_cached("space_temp_inc.htm",$flag))
{	
	$company=$shop->user_detail($_GET['uid']);
	if($company['shop_statu']!=1)
	{
		msg("$config[weburl]/home.php?uid=$_GET[uid]","商铺还未开启，或暂时关闭,将转向个人主页");
	}
	else
	{
		//-----------------语言包--------------------
		include_once("lang/".$config['language']."/user_space.php");
		$dir=$config['webroot'].'/module/';
		$handle = opendir($dir); 
		while ($filename = readdir($handle))
		{ 
			if($filename!="."&&$filename!="..")
			{
				if(file_exists($dir.$filename.'/config.php'))
				{ 
					include("$dir/$filename/config.php");
				}
		   }
		}
		ksort($shopconfig['menu']);
		$tpl->assign("nav_menu",$shopconfig['menu']);
		//------------信息名－自定义－企业名－网站名-------------------
		$config_file=$config['webroot']."/config/shop_config/shop_config_".$_GET['uid'].'.php';
		if(file_exists($config_file))
		{
			include($config_file);			
		}
		$shopconfig["hometitle"].='-'.$company['company'];
		$shopconfig["homedes"].=','.$company['company'].','.$company['main_pro'];
		$shopconfig["homekeyword"].=','.$company['company'].','.$company['main_pro'];
		//-------------使用指定店铺模板。-----------------------------
		if(!empty($_GET['template']))
		{
			if(file_exists($config['webroot']."/templates/$_GET[template]"))
				$company['template']=$_GET['template'];
		}
		if(empty($company['template']))
			$company['template']='user_templates_default';
		$tpl -> template_dir = $config['webroot'] . "/templates/".$company['template']."/";
		$tpl -> compile_dir = $config['webroot'] . "/templates_c/".$company['template']."/";
		$tpl -> assign("imgurl","templates/".$company['template']."/img/");
		//-----------------------------------------------------
		$tpl->assign("ulink",$shop->get_user_link());
		$tpl->assign("score",$shop->score());
		$tpl->assign("custom_cat",$shop->get_custom_cat_list(1));
		$tpl->assign("shop_nav",$shop->get_shop_nav());
		//-------------------------module分发--------------------
		if(!empty($_GET['m'])&&!empty($_GET['action']))
		{
			if(file_exists("$config[webroot]/module/".$_GET['m']."/lang/".$config['language'].".php"))
				include("$config[webroot]/module/".$_GET['m']."/lang/".$config['language'].".php");//#调用模块语言包
			include("module/".$_GET['m']."/space_".$_GET['action'].".php");
			$tpl -> template_dir = $config['webroot'] . "/templates/".$company['template']."/";
		}
		else
		{
			//-----------------------------------------
			
			$sql="select * from ".PRO." where shop_rec=1 and userid='$_GET[uid]' order by id limit 0,8";
			$db->query($sql);
			$re=$db->getRows();
			$tpl->assign("rec_pro",$re);
			
			$sql="select * from ".PRO." where userid = '$_GET[uid]' and statu>0 order by id desc limit 0,12";
			$db->query($sql);
			$de=$db->getRows();
			$tpl->assign("pro",$de);
			//-------------------------------------------
			
			$page  ="space_index.htm";
		}
		//--------------------------------------------
		$tpl->assign("cs",$shop->get_cs());
		$tpl->assign("shopconfig",$shopconfig);
		$tpl->assign("com",$company);
		include_once("footer.php");
		//----------------------------------------------
		if(empty($output))
			$tpl->assign("output",$tpl->fetch($page?$page:"space_company.htm",$flag));
		else
			$tpl->assign("output",$output);
	}

}
$tpl ->display("space_temp_inc.htm",$flag);
?>