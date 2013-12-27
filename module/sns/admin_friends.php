<?php
include_once("$config[webroot]/module/sns/includes/plugin_friend_class.php");
$friend=new friend();
//============================================================

if(isset($_GET['delid'])&&is_numeric($_GET['delid']))
{
	$friend->del_friend_info($_GET['delid']);
}
//获取好友
$tpl->assign("re",$friend->GetFriendList());

//==================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_friends.htm");
?>