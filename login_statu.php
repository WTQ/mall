<?php
include_once("includes/global.php");
//=================================
function showUser()
{
	global $config,$db,$buid;
	$new=$inum=0;
	include($config["webroot"]."/lang/".$config['language']."/front.php");
	
	if($buid)
	{
		include_once($config["webroot"]."/includes/plugin_msg_class.php");
		$msg=new msg();
		$new=$msg->getMailNum();
		$new=$new['new'];
		if($new>0)
		{
			$new=" | <BGSOUND balance=0 src='$config[weburl]/image/default/newmsg.wma' volume=-600 loop=0><a href='$config[weburl]/main.php?action=admin_mail_list'><img src='$config[weburl]/image/default/notice_newpm.gif' />".$lang['new_msg']."($new)</a> ";
		}
		else
			$new=NULL;
	}
	//$getstr=count($_GET)?'?'.implode('&',convert($_GET)):NULL;
	if(!empty($_COOKIE['USER']))
		$new=$lang["hello"].',<a href=\'main.php\'>'.$_COOKIE['USER']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='$config[weburl]/main.php?action=logout'>".$lang["logout"]."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	else
		$new=$lang["tourist"]."&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".$config["weburl"]."/$config[regname]'>".$lang["sigin"]."</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".$config["weburl"]."/login.php'>".$lang["login"]."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
		return $new;
}
echo "document.write(\"".showUser()."\");";
?>