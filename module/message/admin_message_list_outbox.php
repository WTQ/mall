<?php
include_once("$config[webroot]/includes/page_utf_class.php");
include_once("$config[webroot]/module/message/includes/plugin_msg_class.php");
$msg=new msg();
//================================================
if(isset($_POST["deid"])&&!empty($_POST['del']))
{
	$msg->del_mail();
}


$msg	 ->save_mail();
$msg	 ->remove_mail();
$msg	 ->recover_mail();
$type='outbox';
$tpl->assign("re",$msg ->mail_list($type));
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_message_list.htm");
?>