<?php
include_once("../includes/global.php");
include_once("../includes/smarty_config.php");
include_once("../config/reg_config.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//---------------------------------
$post=$_GET;
$config = array_merge($config,$reg_config);
if(!empty($post["action"])&&$post["action"]=="submit")
{
	if($config['openbbs']==2)
	{	//ucenter1.5 login
		$sql="select a.userid,a.user,a.password,a.email from ".ALLUSER." a where a.user='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();//bb用户是否存在
		include_once('../uc_client/client.php');
		list($uid, $username, $password, $email) = uc_user_login($re['user'], $re['password']);//uc是否存在
		
		if($uid>0||$re["userid"])//如果uc或者BB之中有一个账户是正确的执行如下操作
		{	
			if($uid<=0&&$re["userid"]>0)//UC不存在ＢＢ存在
			{
				$uid = uc_user_register($re['user'], $re['password'], $re['email']);
			}
			login($re['userid'],$re['user']);//网站登录
			echo uc_user_synlogin($uid);//ＵＣ同步登录
			msg("../main.php");
		}
		else
		{
			header("Location: login.php?erry=-1");//用户不存在
			exit();
		}
	}
	else
	{	
		// no ucenter login
		$sql="select * from ".ALLUSER." where user='$post[user]'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re["userid"])
		{
			login($re['userid'],$re['user']);
			msg('../main.php');
		}
	}
}
//========================================================
function login($uid,$username)
{
	global $config;
	bsetcookie("USERID","$uid\t$username",time()+60*60*24,"/",$config['baseurl']);
	setcookie("USER",$username,time()+60*60*24,"/",$config['baseurl']);
	unset($_SESSION["IFPAY"]);
}
?>