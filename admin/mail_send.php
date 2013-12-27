<?php
@set_time_limit(0);
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");

//============================================
if(!empty($_POST["catid"])&&$_POST["catid"][0]!='')
{
	$catsql=implode("','",$_POST["country"]);
	$sql0=" and b.country in ('$catsql') ";
}
if(!empty($_POST["catid"])&&$_POST["catid"][0]!='')
{
	$catsql=implode("','",$_POST["catid"]);
	$sql1=" and b.catid in ('$catsql') ";
}
if(!empty($_POST["province"]))
{
	$sql2.=" and b.provinceid='$_POST[province]'";
}
if(!empty($_POST["city"]))
{
	$sql2.=" and b.cityid='$_POST[city]'";
}
if(!empty($_POST["area"]))
{
	$sql2.=" and b.areaid='$_POST[area]'";
}
if(!empty($_POST["unlogotime"]))
{
	$st=$_POST["unlogotime"];
	$unltime=strtotime(date("Y-m-d H:i:s"))-$st*60*60*24;
	$sql4=" and a.lastLoginTime<$unltime ";
}

if(!empty($_POST["mes"]))
	$mcontent=stripslashes($_POST["mes"]);
else
{
	echo "content is null！";
	exit;
}
if (!empty($_POST["subject"]))
	$mtitle=$_POST["subject"];
else
{
	echo "title is null！";
	exit;
}
if (empty($_POST["mtype"]))
{
	echo "please check type!";
	exit;
}
$sqlw=$sql0.$sql1.$sql2.$sql3.$sql4;
$limits=0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</HEAD>
<body>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('send_mail_multiply');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellspacing="1" cellpadding="3" height="800" bgcolor="#1595C7">
      <tr>
        <td height="30"><?php echo lang_show('msg');?></td>
      </tr>
      <tr>
        <td width="740" height="600" valign="top" ><?php
		if(!empty($_POST["action"])&&$_POST["action"]=="send"&&$_POST["mtype"]==1)
		{
			  echo "<iframe src=\"send_mail_back.php?limit=0&sqlw=".urlencode($sqlw)."&mailcontent=".urlencode($mcontent)
			  ."&mailtitle=".urlencode($mtitle)."\" width='100%' height='100%' frameborder='0' scrolling='yes'></iframe>";
		}
		if(!empty($_POST["action"])&&$_POST["action"]=="send"&&$_POST["mtype"]==2)
		{
			  echo "短信息发送函数代码添加在mail_send.php 77行 于此！请与官方联系添加此接口！";
		}
		if(!empty($_POST["action"])&&$_POST["action"]=="send"&&$_POST["mtype"]==3)
		{
			$sqla="select id from ".ADMIN." where user='".$_SESSION["ADMIN_USER"]."'";
			$db->query($sqla);
			$de=$db->fetchRow();
			if (!empty($de['id']))
				$fromid=$de['id'];
			else
				$fromid="1";
			$sqls="select a.userid,a.user,a.lastLoginTime,a.email,b.contact,b.company from ".ALLUSER." a, ".USER." b where 
			a.userid=b.userid and a.email<>'' $sqlw order by a.userid desc";
			
			
			$db->query($sqls);
			$res=$db->getRows();
			$i=0;
			foreach($res as $v)
			{
				$mcontent=str_replace('[username]',$v['user'],$mcontent);
				$mcontent=str_replace('[company]',$v['company'],$mcontent);
				$mcontent=str_replace('[lastlogintime]',date("Y-m-d H:i:s",$v['lastLoginTime']),$mcontent);
				$bday=ceil((time()-$v['lastLoginTime'])/86400);
				$mcontent=str_replace('[betweenday]',$bday,$mcontent);
			   if(!empty($_POST["contact"])&&!empty($_POST["email"])&&!empty($_POST["tel"]))
				{
					$fromInfo="Name:$_POST[contact]<br>Email:$_POST[email]<br>Tell:$_POST[tel]";
					$sql="insert into ".FEEDBACK." (touserid,fromInfo,sub,con,date) VALUES
					('".$v['userid']."','".$fromInfo."','".$mtitle."','".$mcontent."','".date("Y-m-d H:i:s")."')";
				}
				else
				{
					$sql="insert into ".FEEDBACK." (touserid,fromInfo,sub,con,date,msgtype) VALUES
					 ('".$v['userid']."','".lang_show('systemsg')."','".$mtitle."','".$mcontent."','".date("Y-m-d H:i:s")."','3')";
				}
			   $db->query($sql);
			   $i=$i+1;
			}
			echo lang_show('mmsgs').$i.lang_show('dot');
		}
	
	  ?>
        </td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
