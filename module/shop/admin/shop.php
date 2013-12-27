<?php
include_once("../includes/page_utf_class.php");
include_once("module/shop/includes/plugin_shop_class.php");
$shop=new shop();
//====================================

function del_user($ar)
{
	global $db,$config;
	foreach($ar as $v)
	{	
		$userid=$v;
		ext_all('delete_by_uid',array('uid'=>$userid));
		$db->query("delete from ".FEED." where userid='$userid'");
		$db->query("delete from ".FEEDBACK." where touserid='$userid' or fromuserid='$userid'");
        $db->query("delete from ".COMMENT." where fromuid='$userid'");
		$db->query("delete from ".READREC." where userid='$userid'");
		$db->query("delete from ".SUBSCRIBE." where userid='$userid'");
		$db->query("delete from ".SHOPEARNEST." where shop_id='$userid'");
	}
}
//-------------------------------
	$get=$_GET;
	unset($get['editid']);
	unset($get['s']);
	unset($get['m']);
	unset($get['grade']);
	unset($get['catid']);
	unset($get['operation']);
	$getstr=implode('&',convert($get));
	$tpl->assign("getstr",$getstr);
	
	if($_GET['operation']=='list')
	{
		if($_POST['act']=='op')
		{
			if(is_array($_POST['chk']))
			{
				del_user($_POST['chk']);
			}
			if($_POST['rank'])
			{
				foreach($_POST['rank'] as $key=>$list)
				{
					$db->query("update ".SHOP." set rank='$list' where userid='$key'");		
				}
			}
			msg("?m=shop&s=shop.php&operation=list",'');
		}	
		$sql=" and shop_statu=1";
		$tpl->assign("de",$shop->GetShopList($sql));
	}
	elseif($_GET['operation']=='edit')
	{		
		if($_POST["act"]=='edit' and is_numeric($_POST['id']))
		{
			$shop->EditShop($_POST['id']);
			
			if($_GET['type'])
				msg("?m=shop&s=shop_$_GET[type].php&operation=list",'');
			else
				msg("?m=shop&s=shop.php&operation=list&$getstr");
		}
		if($_GET['editid'] and is_numeric($_GET['editid']))
		{
			$de=$shop->GetShop($_GET['editid']);
			if($de['stime'])
				$de['stime']=date("Y-m-d",$de['stime']);
			else
				$de['stime']=date("Y-m-d");
			if($de['etime'])
				$de['etime']=date("Y-m-d",$de['etime']);
			else
				$de['etime']=(date("Y")+1).'-'.date("m-d");
			
			//获取店铺商品数量
			$de['product_num']=$shop->GetProductNum($_GET['editid']);
			//获取店铺分类名
			$de['cat']=$shop->GetShopCatName($de['catid']);
			$tpl->assign("de",$de);
			
			//-------------信誉
			$cre=explode('|',$config['credit']);
			if($cre)
			{
				foreach($cre as $key=>$v)
				{
					$nkey=pow(2,$key);
					$credit[$nkey]=$v;
				}
				$tpl->assign("credit",$credit);
			}
			//-------------推荐
			$rc_member = array(
				'0'=>$lang['norec'],
				'1'=>$lang['reccom'],
				'2'=>$lang['startcom'],
			);
			$tpl->assign("rc_member",$rc_member);
		}
	}
	//获取店铺类型
	$tpl->assign("grade",$shop->GetShopGradeList());
	//获取店铺分类
	$tpl->assign("catlist",$shop->GetShopCatList());
	//获取地区
	$tpl->assign("prov",GetDistrict());
	
	$tpl->assign("config",$config);
	$tpl->display("admin/shop.htm");
?>