<?php
/**
 * 注册页面
 * Powered by B2Bbuilder
 */
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("config/reg_config.php");
$config = array_merge($config,$reg_config);
//====================================================
if(!empty($_POST['invite'])&&!empty($buid))
{
	$sql="update ".ALLUSER." set invite='$_POST[invite]' where userid='$buid'";
	$db->query($sql);
	msg("main.php");
	die;
}
//----------------------------------------------------
if(!empty($_GET['rtype']))
	$config['detail_reg']=1;//注册企业会员
else
	$config['detail_reg']=0;//注册个人会员
	
if(is_array($stop_reg))
{
	stop_ip($stop_reg);
	unset($stop_reg);
}
if($buid&&$config['user_reg']!=3&&empty($_GET['success']))
{	//已经登录
	msg('main.php');
}
//----------------------------------------------------
session_start();
if(!empty($_POST['user'])&&strtolower($_POST['yzm'])==strtolower($_SESSION['auth']))
{
	if($config['closetype']==2)
	{//关闭注册
		die('access dined!');
	}
	if($config['user_reg_verf'])
	{//验证码不对
		if(trim($_POST['ckyzwt'])!=trim($_SESSION['YZWT']))
			 die("Verification question error...");
	}
	if($config['inhibit_ip']==1)
	{//ip禁止注册
		$ip=getip();
		if(empty($ip))
			die("Can not get you IP...");
		else
		{	
			$config['exception_ip']=explode("\r\n",$config['exception_ip']);
			if(!in_array($ip,$config['exception_ip']))
			{
				$sql="select ip from ".ALLUSER." where ip='$ip'";
				$db->query($sql);
				if($db->num_rows())
					die("Your IP has been registered...");
				unset($sql);
			}
		}
	}
	if($config['openbbs']==2)
	{//关联UCHENTER
		include_once('uc_client/client.php');
		$user=trim($_POST['user']);
		$pass=trim($_POST['password']);
		$email=trim($_POST['email']);
		$regtime=time();
		$uid = uc_user_register($user, $pass, $email);
		if($uid>0)
		{
			doreg($uid);
			/*免激活
			$sql="insert into cdb_members
		    (uid,username,password,groupid,regip,regdate,email,timeoffset,lastvisit,lastactivity)
		     values
		     ('$uid','$user','$pass','10','hidden','$regtime','$email','9999','$regtime','$regtime')";
		    $db->query($sql);
			*/
		}
	}
	else
		doreg();
}

function doreg($guid=NULL)
{
	global $config,$ip;
	$_POST['sex']=empty($_POST['sex'])?0:$_POST['sex'];
	$_POST['identity']*=1;
	$user=$_POST['user'];
	$pass=$_POST['password'];
	$email=$_POST['email'];
	$provinceid=$_POST['provinceid']*1;
	$ip=getip();$ip=empty($ip)?NULL:$ip;
	$nt=time();$regtime=date("Y-m-d H:i:s");

	$db=new dba($config['dbhost'],$config['dbuser'],$config['dbpass'],$config['dbname'],$config['dbport']);	

	$sql="select * from  ".ALLUSER." where user='$user' or email='$email'";
    $db->query($sql);
    if($db->num_rows())
		die("User name is have");
	//----------------
	if(!empty($config['user_reg'])&&$config['user_reg']!=3)
		$user_reg=$config['user_reg'];
	elseif($config['user_reg']==3)
		$user_reg=1;
	else
		$user_reg=2;
	//----------------		
	$sql="insert into ".ALLUSER."
	 (user,password,ip,lastLoginTime,skype,msn,sex,mobile,position,email,regtime,statu,name)
	 values
	 ('$user','".md5($pass)."','$ip','$nt','$_POST[qq]','$_POST[skype]','$_POST[sex]','$_POST[mobile]','$_POST[pos]','$email','$regtime','$user_reg','$_POST[realname]')";
	$re=$db->query($sql);
	if($userid=$db->lastid())
	{	
		if(isset($config['detail_reg'])&&$config['detail_reg']==1)
		{	
			//打开详细注册
			$sql="INSERT INTO ".USER." 
			(userid,user,template,provinceid,company,tel,addr,uptime) 
			VALUES 
			('$userid','$user','$config[default_user_tem]','$provinceid','$_POST[comname]',
			'$_POST[tel]','$_POST[addr]','$nt')";
			$re=$db->query($sql);
		}
		if($re)
		{
			//---------------绑定一键连接
			if(!empty($_POST['connect_id']))
			{
				$sql="update ".USERCOON." set userid='$userid' where id='$_POST[connect_id]'";
				$db->query($sql);
			}
			//---------------设置加密的cookie
			bsetcookie("USERID","$userid\t$user",time() + 36000,"/");
			setcookie("USER",$user,time() + 36000,"/");
			//============================================================
			if($config['openregemail'])
			{	//注册欢迎邮件发送
				 $mail_temp=get_mail_template('register');
				 $con=$mail_temp['message'];
				 $ar1=array('[username]','[email]','[weburl]');
				 $ar2=array($user,$email,$config['weburl']);
				 $con=str_replace($ar1,$ar2,$con);
				 send_mail($email,$user,$config['company'],$con);
				 unset($con);
			}
			//============================================================
			if($config['user_reg']==3)
			{	
				//邮件认证
				$rand=rand(10000,99999);
				$link=$config['weburl'].'/'.$config['regname'].'?rand='.$rand;
				$sql="update ".ALLUSER." set rand='$rand' where userid='$userid'";
				$db->query($sql);
				$mail_temp=get_mail_template('active');
				$con=$mail_temp['message'];
				$ar1=array('[username]','[email]','[weburl]','[link]');
				$ar2=array($user,$email,$config['weburl'],$link);
				$con=str_replace($ar1,$ar2,$con);
				send_mail($email,$user,$config['company'],$con);
				unset($con);
				//提醒查收邮件,进行账号激活。
				header("Location: $config[regname]?active=1");
				exit();
			}
			else
			{
				//注册成功页面
				header("Location: $config[regname]?success=1&guid=$guid");
				exit();
			}
		}
	 }
	 else
		 die("Can not register...");
}
//====================================================
if(!empty($_GET['re_email']))
{
	//如果没有收到邮件，就进行重发
	$rand=rand(10000,99999);
	$link=$config['weburl'].'/'.$config['regname'].'?rand='.$rand;
	$sql="update ".ALLUSER." set rand='$rand' where userid='$buid'";
	$db->query($sql);
	
	$sql="select user,email from ".ALLUSER." where userid='$buid'";
	$db->query($sql);
	$ue=$db->fetchRow();
	$mail_temp=get_mail_template('active');
	$con=$mail_temp['message'];
	$ar1=array('[username]','[email]','[weburl]','[link]');
	$ar2=array($ue['user'],$ue['email'],$config['weburl'],$link);
	$con=str_replace($ar1,$ar2,$con);
	send_mail($ue['email'],$ue['user'],$config['company'],$con);
	header("Location: $config[regname]?active=1");
	exit();
}
if(!empty($_GET['rand']))
{
	$sql="select user,userid,rand from ".ALLUSER." where rand='$_GET[rand]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($_GET['rand']*1==$re['rand'])
	{	
		//链接激活成功
		$sql="update ".ALLUSER." set statu=2 where userid=$re[userid]";
		$db->query($sql);
		//设置SESSION
		$_SESSION["STATU"]=2;
		if($config['openbbs']==2)
			header("Location: $config[regname]?success=1&guid=$re[userid]");
		else
			header("Location: $config[regname]?success=1");
		exit();
	}
	else
	{	
		//验证码填写错误
		header("Location: register.php?active=1&randErr=1");
		exit();
	}
}
//=====================================================
@include_once('config/module_company_config.php');
$config = array_merge($config,$module_company_config);
//-----------------------------------------------------
if(isset($config['detail_reg'])&&$config['detail_reg']==1)
	$template="register_detail.htm";
if(!empty($_GET['active']))
	$template="register_active.htm";
if(!empty($_GET['success']))
{
	$sql="select name from ".ADMIN." where 1 order by name";
	$db->query($sql);
	$invite=$db->getRows();
	$tpl->assign("invite",$invite);
	$template="register_success.htm";
}
if(empty($template))
	$template="register.htm";
//-----------------------------------------------------
include_once("footer.php");	
if($config['language']=='en')
{
	$tpl->assign("output",$tpl -> fetch($template));
	$tpl->display("register_inc.htm");
}
else
	$tpl->display($template);
?>