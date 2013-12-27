<?php
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("../includes/global.php");
@session_start();
$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
include_once("../lang/" . $config['language'] . "/admin.php");
//===========================================
if(empty($_SESSION["ADMIN_USER"])||empty($_SESSION["ADMIN_PASSWORD"]))
	msg('index.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<title><?php echo lang_show('business_manager_system');?></title>
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('manager_home');?></div>
	<div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
		  <tr class="theader">
		    <td colspan="4" align="left">待处理信息</td>
      	  </tr>
	     <tr>
            <td align="left"><strong>待审核会员</strong></td>
            <td>
			<a href="member.php?only=1&ordrby=lastLoginTime&category=user&Submit=搜索">
			<?php $sql="select count(*) as num from ".ALLUSER." where statu='1'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
            <td><strong>待审核产品</strong></td>
            <td>
			<a href="module.php?type=0&statu=0&order=1&title=1&Submit=搜索&m=product&s=prolist.php">
			<?php $sql="select count(*) as num from ".PRO." where statu='0'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
		 </tr>
		  <tr>
            <td align="left"><strong>到期广告数</strong></td>
            <td>
			<a href="advs_con_list.php?endtime=1">
			<?php $time=time()-3600*24*7;$sql="select count(*) as num from ".ADVSCON." where etime<='$time'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
            <td><strong>到期会员数</strong></td>
            <td>
			<a href="module.php?ordrby=uptime&Submit=搜索&m=shop&s=shop.php&endtime=1">
			<?php $time=time()-3600*24*7;$sql="select count(*) as num from ".USER." where etime<='$time'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
		  </tr>
          <tr>
            <td align="left"><strong>待审核新闻</strong></td>
            <td>
			<a href="module.php?m=news&s=newslist.php&seltype=3&key=&field=0&classid=&submit=搜索&nclass=">
			<?php $sql="select count(*) as num from ".NEWSD." where ispass='0'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
            <td><strong>会员提问数</strong></td>
            <td>
			<a href="feedback.php?noread=1">
			<?php $sql="select count(*) as num from ".FEED." where iflook='0'";$db->query($sql);echo $db->fetchField('num');?>
			</a>
			</td>
          </tr>
		   <?php
            $lip=getip();
			$nt=time();
		    $sql="update ".ADMIN." set logoip='$lip',lastlogotime='$nt' where user='".$_SESSION["ADMIN_USER"]."'";
            $db->query($sql);
		  ?>
		            <tr class="theader">
            <td height="20" colspan="4" align="left"><?php echo lang_show('myinfo');?></td>
          </tr>
          <tr>
            <td width="10%" align="left"><?php echo lang_show('yourgroup');?></td>
            <td width="27%"><?php echo $_SESSION["ADMIN_USER"];?></td>
            <td width="11%"><?php echo lang_show('lastlogoip');?></td>
            <td width="52%"><?php echo $re['logoip'];?></td>
          </tr>
          <?php 
		  $sql="select * from ".ADMIN." where user='".$_SESSION["ADMIN_USER"]."'";
		  $db->query($sql);
          $re=$db->fetchRow();
		  ?>
		   <tr>
            <td width="10%" align="left"><?php echo lang_show('logoip');?></td>
            <td><?php echo getip();?></td>
            <td><?php echo lang_show('todayis');?></td>
            <td><?php echo date("Y-m-d H:i:s", time());?></td>
	      </tr>
          <tr>
            <td align="left"><?php echo lang_show('lastlogotime');?></td>
            <td><?php echo date("Y-m-d H:i:s",$re['lastlogotime']);?></td>
            <td><?php echo lang_show('logonum');?></td>
            <td><?php echo $re['logonums'];?></td>
          </tr>
      </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
          <tr class="theader">
            <td height="20" colspan="2" align="left">系统信息</td>
          </tr>
		  <tr>
		    <td align="left"><?php echo lang_show('vers');?></td>
		    <td><?php echo $config['version'];?></td>
      	</tr>
          <tr>
            <td height="10" align="left" valign="middle">服务器信息</td>
            <td height="10" align="left" valign="middle" ><?php echo $_SERVER['SERVER_SIGNATURE'];?></td>
          </tr>
          <tr>
            <td height="20" align="left" valign="middle" >数据库版本</td>
            <td height="20" align="left" valign="middle" ><?php echo mysql_get_server_info();?></td>
          </tr>
        </table>
	</div>
</div>
</body>
</html>