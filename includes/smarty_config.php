<?php
include_once($config["webroot"]."/includes/insert_function.php");
include_once($config["webroot"]."/lib/smarty/Smarty.class.php");
include_once($config["webroot"]."/config/nav_menu.php");
include_once($config['webroot']."/config/stop_ip.php");
include_once($config['webroot']."/config/cron_config.php");
include_once($config['webroot']."/includes/arrcache_class.php");
include_once($config['webroot']."/config/seo_config.php");
//==================================================
if(!empty($config['enable_gzip']) && function_exists('ob_gzhandler'))
	ob_start('ob_gzhandler');
//=================================================
$db->cache_valid=FALSE;//使用缓存
//=================================================
$b2bbuilder_auth=bgetcookie("USERID");
$buid=$b2bbuilder_auth['0'];
if(!empty($cron_config['nexttransact'])&&$cron_config['nexttransact']<=time())
{
	$systime=time();
	include_once($config['webroot']."/includes/cron_inc.php");
	execute_transact();
}
//==================================================
if(isset($config['closetype'])&&$config['closetype']=='1')
{
	echo '<center>------------------------------------------------------<br/>';
	echo $config['closecon'];
	echo '<br/>------------------------------------------------------</center>';
	die;
}
//------------------------------------------------
if($buid)
{
	session_start();
	if(isset($_SESSION["IFPAY"])&&$_SESSION["IFPAY"]==-2)
	{
		echo '<center>------------------------------------------------------<br/>';
		echo '禁止访问';
		echo '<br/>------------------------------------------------------</center>';
		die;
	}
}
//----------------------------------------------
if(is_array($stop_view))
{
	stop_ip($stop_view);
	unset($stop_view);
}
//-------------------------------------------------
if($config['domaincity']&&!empty($config['baseurl']))
{
	$dre=explode(".",$_SERVER['HTTP_HOST']);
	$prefix=array_shift($dre);
	if($_SERVER['HTTP_HOST']==$prefix.'.'.$config['baseurl']&&!isset($_GET['uid']))
	{
		$sql="select con,con2,web_title,web_keyword,web_des,des,copyright,template,logo from ".DOMAIN." where domain='".$prefix."'";
		$db->query($sql);
		$do=$db->fetchRow();
		if(is_array($do))
		{
			$config['title']  		= 	$do['web_title'];
			$config['keyword']		= 	$do['web_keyword'];
			$config['description']	= 	$do['web_des'];
			$config['company']  	= 	$do['web_title'];
			$config['copyright']	=	$do['copyright'];
			$config['site_des']		= 	$do['des'];
			$config['logo']			=	$do['logo'];
			if(!empty($do['template']))
				$config['temp']		=	$do['template'];
			setcookie("SPID",$do['con'],time()+60*60*24*30,'/');
			setcookie("SCID",$do['con2'],time()+60*60*24*30,'/');
			
			$dpid=!empty($do['con'])?$do['con']:'';
			$dcid=!empty($do['con2'])?$do['con2']:'';
			$config['weburl']="http://".$prefix.".".$config['baseurl'];
		}
	}
	unset($dre);unset($do);unset($exception);
}
//------------------------------------------
//如果选译了城市，或者开启了二级域名，并且二级域名存在
if(!empty($_COOKIE['PID'])&&empty($dpid))
	$dpid=$_COOKIE['PID'];
if(!empty($_COOKIE['CID'])&&empty($dcid))
	$dcid=$_COOKIE['CID'];
//-----------------------------------------
//搜索词入库
if(!empty($_GET['key']))
{
	$db->query("select id from ".SWORD." where keyword='$_GET[key]'");
	if($db->fetchField('id'))
		$sql="update ".SWORD." set nums=nums+1 where keyword='$_GET[key]'";
	else
	{
		include_once('lib/allchar.php');
		$str=addslashes(c($_GET['key']));
		$sql="insert into ".SWORD." (keyword,char_index,nums) values ('$_GET[key]','$str','1')";
	}
	$db->query($sql);
}
//----------是否开通统计---------------
if($config['openstatistics'])
{
	if(!isrobot())
	{
		global $db;
		$user=empty($_COOKIE['USER'])?NULL:$_COOKIE['USER'];
		$time=date("Y-m-d H:i:s");
		$ip=getip();
		$sql="insert into ".PV."
		(url,ip,time,username,fileName)
		values
		('".substr(urlencode($_SERVER['REQUEST_URI']),0,30)."','$ip','$time','$user','".substr($_SERVER['SCRIPT_NAME'],0,30)."')";
		$db->query($sql);
	}
}
//--------------------------------------
if(!empty($_GET["temp"]))
{
	setcookie("temp",$_GET["temp"]);
	msg($config['weburl']);
}
if(!empty($_COOKIE['temp']))
	$config['temp']=$_COOKIE['temp'];
//---------------------------------------
$tpl =  new Smarty();
$tpl -> left_delimiter  = "<{";
$tpl -> right_delimiter = "}>";
$tpl -> template_dir    = $config["webroot"] . "/templates/".$config['temp']."/";
$tpl -> compile_dir     = $config["webroot"] . "/templates_c/".$config['temp']."/";

$tpl -> assign("buid",$buid);
$tpl -> assign("menus",$nav_menu);
unset($nav_menu);
?>