<?php
include_once("$config[webroot]/module/message/includes/plugin_msg_class.php");
$msg=new msg();
//============================================================
if(isset($_POST['msgsend'])&&$_POST['sendid']!=',')
{
	$msg->friend_msg_batch_send();
}
//------------------邮件详情
if(!empty($_GET['id']))
{
	$de=$msg ->mail_det($_GET['id']);
	$tpl->assign("de",$de);
	$_GET['uid']=$de['fromuserid'];
}
//--------------------收件人
if(!empty($_GET['uid']))
{
	$sql="select user from ".ALLUSER." where userid='$_GET[uid]'";
	$db->query($sql);
	$tpl->assign("auser",$db->fetchField('user'));
}
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_message_sed.htm");
?>