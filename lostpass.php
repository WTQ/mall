<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=========================================
$page="lostpass.htm";
if(!empty($_GET['msg']))
{
	$page="reset_pass.htm";
}
if(!empty($_POST['resetpass'])&&!empty($_POST['newpass'])&&!empty($_GET['md5']))
{
	//重设密码
	if($_POST['newpass']!=$_POST['newpass1'])
		msg("lostpass.php?msg=1&userid=$_GET[userid]&md5=$_GET[md5]");
	else
	{
		$db->query("update ".ALLUSER." set password='".md5($_POST['newpass'])."'
		           where userid='$_GET[userid]' and password='$_GET[md5]'");
		msg("lostpass.php?msg=2");
	}
}
if(!empty($_GET['md5'])&&!empty($_GET['userid']))
{	
	//调出重设密码的模板
	$sql="select * from ".ALLUSER." where userid='$_GET[userid]' and password='$_GET[md5]'";
	$db->query($sql);
	$uid=$db->fetchField('userid');
	if($uid)
		$page='reset_pass.htm';
}
if(!empty($_POST["action"])&&$_POST["action"]=="com"&&!empty($_POST['user']))
{//根据用户名和密码确定是哪一个公司在找回密码
	$sql="select * from ".ALLUSER." where user='$_POST[user]' and email='$_POST[email]'";
	$db->query($sql);
	$re=$db->fetchRow();
	$tpl->assign("company",$re);
}
//===============================
if(!empty($_POST["action"])&&$_POST["action"]=="submit")
{
	$info=explode("|",$_POST["userid"]);
	$sql="select * from ".ALLUSER." where userid='$info[0]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re["userid"])
	{
		$md5=md5(time().rand(0,100));
		$md5='lock'.substr($md5,5,strlen($md5));
		$db->query("update ".ALLUSER." SET password='$md5' where userid='$info[0]'");
		
		$mail_temp=get_mail_template('find_pwd');
		$con=$mail_temp['message'];
		$url=$config['weburl']."/lostpass.php?md5=$md5&userid=$re[userid]";
		$url="<a target='_blank' href='".$url."'>".$url."</a>";
		
		$ar1=array('[sitename]','[username]','[findurl]','[contact]');
		$ar2=array($config['company'],$re['user'],$url,$re['name']);
		$con=str_replace($ar1,$ar2,$con);
		send_mail($info[1],$re["user"],$config['company']." PASSWORD",$con);
		$tpl->assign("email",$info[1]);
	}
	else
		msg("lostpass.php");
	$tpl->assign('p_email',$info[1]);
	$page="lostpass_steptwo.htm";
}
//===========页面底部===============
include_once("footer.php");
if($config['language']=='en')
{
	$tpl->assign("output",$tpl -> fetch($page));
	$tpl->display("register_inc.htm");
}
else
	$tpl->display($page);
?>