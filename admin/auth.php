<?php
@session_start();
if(empty($_SESSION["ADMIN_USER"])||empty($_SESSION["ADMIN_PASSWORD"]))
{
	echo '<SCRIPT   LANGUAGE="JavaScript">   
	top.location.href="index.php";   
	</SCRIPT>';
	die;
}	
if(empty($sctiptName))
{
	msg("noright.php");
	exit();
}	
if(!isset($_SESSION["ADMIN_USER"]))
	$_SESSION["ADMIN_USER"]=NULL;
//=================================
$p=NULL;
if(!empty($_POST)&&is_array($_POST))
{
	foreach($_POST as $v)
	{
		if(is_array($v))
			$p.=implode(",",$v);
		else
			$p.=','.$v;
	}
}
	
if($p!='')
	$p=csubstr($_SERVER['REQUEST_URI'].'&post='.$p,0,30);
else
	$p=csubstr($_SERVER['REQUEST_URI'],0,50);
$p=htmlspecialchars($p);
$sql="insert into ".OPLOG." (user,scriptname,url,time) values ('$_SESSION[ADMIN_USER]','$sctiptName','$p','".time()."')";
$db->query($sql);

//===============================
if(!empty($sctiptName))
{
	if($_SESSION["ADMIN_TYPE"]=="1")
	{
		$sql="SELECT * FROM ".ADMIN."  WHERE user='".$_SESSION["ADMIN_USER"]."' AND password='".$_SESSION["ADMIN_PASSWORD"]."'";
	}
	else
	{
		$sql="
			SELECT
			  a.province,a.city,a.area,a.id,b.group_perms
			FROM
			  ".ADMIN." a left join ".GROUP." b on a.group_id=b.group_id
			WHERE
			    a.user='".$_SESSION["ADMIN_USER"]."' AND a.password='".$_SESSION["ADMIN_PASSWORD"]."'";
	}
	$db->query($sql);unset($sql);
	$re=$db->fetchRow();
	if(!$re["id"])
	{	
		msg("index.php");//用户名或密码错误
	}
	else
	{
		if(!empty($re["province"])&&!isset($_SESSION["city"]))
		{
			$_SESSION["province"]=$re["province"];
		}
		if(!empty($re["city"])&&!isset($_SESSION["city"]))
		{
			$_SESSION["city"]=$re["city"];
		}
		if(!empty($re["area"])&&!isset($_SESSION["area"]))
		{
			$_SESSION["area"]=$re["area"];
		}
		if(!empty($re["group_perms"]))
		{
			$perm=explode(",",$re["group_perms"]);
			if(!in_array(md5($sctiptName),$perm)&&$sctiptName!='main.php')
			{
				msg("noright.php");
				exit();
			}
		}
	}
}
else
{
	if($_SESSION["ADMIN_TYPE"]!="1"&&$sctiptName!="main.php")
	{
		msg("noright.php");
		exit();
	}
}
//===========================================
$delimg='<img src="../image/admin/delete.png">';
$editimg='<img src="../image/admin/edit.png">';
$addimg='<img src="../image/admin/add.gif">';
$onimg='<img src="../image/default/on.gif">';
$offimg='<img src="../image/default/off.gif">';
$startimg='<img src="../image/admin/start.png">';
$stopimg='<img src="../image/admin/stop.png">';
$setimg='<img src="../image/admin/set.png">';
$mailimg='<img src="../image/default/icon_mail.gif">';

$langs = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
include_once($config['webroot']."/lang/".$langs."/admin.php");
?>