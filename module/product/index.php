<?php
/***
 *Powered by b2bbuilder
 *Copyright http://www.b2b-builder.com
 *Auther:Brad zhang
 *Date:2008-11-7
 *Des:产品类别页面，已做缓存处理
 */
//=============================================
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
//------------------------------
if(!empty($config['index_catid']))
	$display_cat=explode(",",$config['index_catid']);
else
	$display_cat=array();
foreach($display_cat as $key=>$v)
{
	//--------推荐产品------------
	$sql="select id,pname,pic,price,market_price from ".PRO."  where  statu=2 and LOCATE($v,catid)=1 order by uptime desc limit 0,10";
	$db->query($sql);
	$cat_pro[$key]['rec_pro']=$db->getRows();
	
	//--------最新产品------------
	$sql="select id,pname,pic,price,market_price from ".PRO." where statu=1 and LOCATE($v,catid)=1 order by uptime desc limit 0,10";
	$db->query($sql);
	$cat_pro[$key]['new_pro']=$db->getRows();
	
	//--------热卖产品------------
	$sql="select id,pname,pic,price,market_price from ".PRO." where statu=1 and LOCATE($v,catid)=1 order by sell_amount desc limit 0,10";
	$db->query($sql);
	$cat_pro[$key]['hot_sell']=$db->getRows();
	
	//--------热卖评产品------------
	$sql="select id,pname,pic,price,market_price from ".PRO." where statu=1 and LOCATE($v,catid)=1 order by  read_nums desc limit 0,10";
	$db->query($sql);
	$cat_pro[$key]['hot_commen']=$db->getRows();
	
	//--------类别名--------------
	$sql="select brand,cat,catid,pic from ".PCAT." where catid='$v'";
	$db->query($sql);
	$cata=$db->fetchRow();
	$cat_pro[$key]['name']=$cata['cat'];
	$cat_pro[$key]['pic']=$cata['pic'];

	//--------类别下面的品牌-------
	if(!empty($cata['brand']))
	{
		$brand_id=$cata['brand'];
		$sql="select id,name,logo from ".BRAND." where 1 and id in($brand_id)";
		$db->query($sql);
		$cat_pro[$key]['brand']=$db->getRows();
	}
	//--------类别下面的子分类------
	$s=$v."00";
	$b=$v."99";
	$sql="select * from ".PCAT." where catid>$s and catid<$b order by nums asc,char_index asc";
	$db->query($sql);
	$cat_pro[$key]['sub_cat']=$db->getRows();
	//------------------------------
	$cat_pro[$key]['catid']=$v;
}
//------团购推荐----
$sql="select * from ".TG." where status=2 order by displayorder limit 0,1";
$db->query($sql);
$re = $db->getRows(); 
$tpl->assign("tj",$re);
//----------------------------
include_once("config/connect_config.php");//connect
$config = array_merge($config,$connect_config);
if($config['sina_connect']==1)//sina
{
	define( "WB_AKEY" , $config['sina_app_id'] );
	define( "WB_SKEY" , $config['sina_key'] );
	define( "WB_CALLBACK_URL" , "$config[weburl]/login.php?type=sina" );
	include_once( 'includes/saetv2.ex.class.php' );
	$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
	$tpl->assign("sina_login_url",$code_url);
}
//------------------------------
$tpl->assign("cat_pro",$cat_pro);
$tpl->assign("current","home");
include_once("footer.php");
//=============================================
$out=tplfetch("product_index.htm",NULL);
?>