<?php
//======================================
if(!empty($_POST['olduser'])&&!empty($_POST['newuser'])&&(trim($_POST['olduser'])!=trim($_POST['newuser'])))
{	
	$old_user=trim($_POST['olduser']);
	$new_user=trim($_POST['newuser']);
	$new_uid=0;
	$old_uid=0;
	$sql="select userid,user from ".ALLUSER." where user IN('$old_user','$new_user')";
	$db->query($sql);
	if($db->num_rows()==2)
	{
		while($list=$db->fetchRow())
		{
			if($list['user']==$old_user)
				$old_uid = $list['userid'];
			if($list['user']==$new_user)
				$new_uid = $list['userid'];
		}
	}

	if($old_uid>0&&$new_uid>0&&$old_uid!=$new_uid)
	{	
		ext_all("update_uid",array('old_uid'=>"$old_uid",'new_uid'=>"$new_uid",'old_user'=>"$old_user",'new_user'=>"$new_user"));
		//=======================
		$sql="update ".COMMENT." set fromuid='$new_uid',fromname='$new_user' where fromuid='$old_uid'";
		$db->query($sql);
	
	
		$sql="update ".FEED." set userid='$new_uid' where userid='$old_uid'";
		$db->query($sql);
		$sql = "update ".FEEDBACK." set touserid=IF(touserid='$old_uid','$new_uid',touserid),fromuserid=IF(fromuserid='$old_uid','$new_uid', fromuserid)   where touserid='$old_uid' or fromuserid='$old_uid'";
		$db->query($sql);
				
		//---------------------------------
		$sql="delete from ".ALLUSER." where userid='$old_uid'";
		$db->query($sql);
		$sql="delete from ".USER." where userid='$old_uid'";
		$db->query($sql);
		//=========================
		admin_msg($_POST['s'].".php","会员'$old_user'、'$new_user'信息合并完成");	
	}
	else
		admin_msg($_POST['s'].".php","会员'$old_user'、'$new_user'信息合并出错");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
<title><?php echo lang_show('admin_system');?></title>
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead">合并会员</div>
	<div class="bigboxbody">
	<form method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr> 
		<td width="11%" align="left">老用户名</td>
		<td width="89%" align="left"><input class="text" type="type" name="olduser" /></td>
		</tr>
		<tr>
		  <td>新用户名</td>
		  <td><input class="text" type="type" name="newuser" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			<input class="btn" type="submit" value="确认提交" />
			<input type="hidden" name="submit" value="merge" />
			<input type="hidden" name="s" value="merge_user" />
			</td>
		</tr>
	</table>
	</form>
	</div>
</div>

</body>
</html>