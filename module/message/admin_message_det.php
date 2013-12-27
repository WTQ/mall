<?php
include_once("$config[webroot]/module/message/includes/plugin_msg_class.php");
//=============================================
$msg=new msg();
if(!empty($_GET['deid']))
{
	$msg->del_mail($_GET['deid']*1);
	msg("main.php?m=message&s=admin_message_list_inbox");//删除后进入收件箱
}
if(!empty($_GET['save_id']))
{
	$msg->save_mail($_GET['save_id']*1);
}
if(!empty($_GET['remove']))
{
	$msg->remove_mail($_GET['remove']*1);
}

if(!empty($_GET['recover']))
{
	$msg->recover_mail($_GET['recover']*1);
}
$tpl->assign("re",$msg ->mail_det($_GET['id']));
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
//=============================================
$output=tplfetch("admin_message_det.htm");
?>