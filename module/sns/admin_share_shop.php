<?php
include_once("$config[webroot]/module/sns/includes/plugin_share_class.php");
$share=new share();
//============================================================

$tpl->assign("re",$share->GetShareShopList());
//删除
if($_GET['type']=='del' and is_numeric($_GET['id']))
{
	$share->DelShareShop($_GET['id']);
	$admin->msg("main.php?m=sns&s=admin_share_shop");
}
//批量删除
if($_GET['pid'])
{
	$pid=explode(',',$_GET['pid']);
	foreach($pid as $val)
	{
		$share->DelShareShop($val);
	}
	die;
}
	
//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_share_shop.htm");
?>