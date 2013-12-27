<?php
include_once("../lang/$langs/company_type_config.php");
//==================================
if(!empty($_POST["action"]))
{
	$_POST['rank']=empty($_POST['rank'])?0:$_POST['rank'];
	$_POST['tj']=empty($_POST['tj'])?0:$_POST['tj'];
	$_POST['stime']=strtotime($_POST['stime']);
	$_POST['etime']=strtotime($_POST['etime']);
	$sql="select userid,email,user from ".ALLUSER." where userid='$_GET[userid]' ";
	$db->query($sql);
	$uid=$db->fetchRow();
	if(!empty($_POST['password'])) 
	{
		$sql="update ".ALLUSER." set password='".md5($_POST['password'])."' where userid='$_GET[userid]'";
		$db->query($sql);
	}
	$sql="update ".ALLUSER." set statu='$_POST[statu]',point='$_POST[point]',regtime='$_POST[regtime]',email='$_POST[email]' where userid='$_GET[userid]'";
	$db->query($sql);
	
	//-------------邮件发送-----------
	if(!empty($_POST['send_mail']))
	{
		$ar1=array('[username]','[weburl]');
		$ar2=array($uid['user'],$config['weburl']);
		$con=str_replace($ar1,$ar2,$_POST['mail_con']);
		send_mail($uid['email'],$ue['user'],$_POST['mail_title'],$con);
	}
	//-------------------------------
	
	unset($_GET['id']);
	unset($_GET['s']);
	unset($_GET['m']);
	msg("?m=member&s=member.php&".implode('&',convert($_GET)));
}
//=====================================================
$sql="select * from ".ALLUSER." where userid='$_GET[userid]'";
$db->query($sql);
$re=$db->fetchRow();
$ip=$re['ip'];
$lastLoginTime=empty($re['lastLoginTime'])?time():$re['lastLoginTime'];
$regtime=empty($re['regtime'])?date("Y-m-d",$lastLoginTime):$re['regtime'];
$statu=$re['statu'];
$point=$re['point'];
//========================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('mem_modify_info');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('mem_modify_info');?></div>
	<div class="bigboxbody">
      <form method="post" action=""  name=cityform>
        <table width='98%' border='0' cellspacing='0' cellpadding='0' align="center" class="menu">
          <tr>
            <td height="24" align="left"><?php echo lang_show('regip');?></td>
            <td>
			<?php 
			if($ip)
			{	
				echo $ip;
				$ipfile="../lib/tinyipdata.dat";
				echo $from = convertip($ip, $ipfile);
			}
			?></td>
          </tr>
          <tr>
            <td height="24" align="left">最后登录时间</td>
            <td><?php echo date("Y-m-d H:i:s",$lastLoginTime) ;?></td>
          </tr>
          <tr> 
			<td width='15%' height="24" align="left"><?php echo lang_show('statu');?></td>
			<td width='92%'>
			  <select class="select" name="statu">
				<?php
				foreach($member_group as $key=>$v)
				{
				?>
				<option value="<?php echo $key; ?>" <?php if($statu==$key) echo "selected"; ?>><?php echo $v; ?></option>
				<?php
				 }
				?>
			  </select>
			  </td>
          </tr>
		  <tr>
		    <td height="24" align="left"><?php echo lang_show('mod_password');?></td>
		    <td><input class="text" type="text" name="password" id="password"></td>
	      </tr>
		  <tr>
		    <td height="24" align="left">注册邮箱</td>
		    <td><input class="text"  name="email" type="text" value="<?php echo $re['email'];?>" /></td>
	      </tr>
		  <tr> 
			<td width='15%' height="24" align="left"><?php echo lang_show('mem_credit');?></td>
			<td width='92%'>
			<input class="text"  name="point" type="text" value="<?php if(!empty($point)) echo $point; else echo 0;?>">			</td>
          </tr>
          <tr>
            <td height="24" align="left"><?php echo lang_show('reg_time');?></td>
            <td><input class="text"  type="text" name="regtime" id="regtime" value="<?php echo "$regtime"; ?>"></td>
          </tr>
          <tr>
            <td height="24">邮件通知</td>
            <td>
				<input class="text" value="<?php $mail_temp=get_mail_template('review_user'); echo $mail_temp['subject']; ?>" name="mail_title" /><br />
				<textarea style="margin-top:5px;" name="mail_con" class="text" cols="" rows="8"><?php echo $mail_temp['message'];?></textarea>
				<br />
				<input type="checkbox" class="checkbox" name="send_mail" value="1" />
				<span class="bz">发邮件通知会员</span>
				</td>
          </tr>
          <tr> 
			<td width='15%' height="24">&nbsp;</td>
			<td width='92%'> 
			  <input class="btn" style="width:50px;" type='submit' name='submit' value='<?php echo lang_show('submit');?>'>
			  <input name="action" type="hidden" id="action" value="submit">			</td>
          </tr>
        </table>
      </form>
	</div>
</div>
</body>
</html>