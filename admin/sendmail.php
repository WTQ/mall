<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//======================================================
if(isset($_POST["action"]))
{
	$mcon=stripslashes($_POST['body']);
	$ntime=strtotime(date("Y-m-d H:i:s")); 
	$db->query("select a.lastLoginTime,a.user,b.company,a.name as contact,a.email from ".ALLUSER." a,".USER." b 
	where a.userid=b.userid and a.userid='$_POST[userid]'");
    $cons=$db->fetchRow();
	$mcon=str_replace('[username]',$cons['user'],$mcon);
	$mcon=str_replace('[company]',$cons['company'],$mcon);
	$mcon=str_replace('[lastlogintime]',date("Y-m-d H:i:s",$cons['lastLoginTime']),$mcon);
	$bday=($ntime-$cons['lastLoginTime'])/86400;
	$mcon=str_replace('[betweenday]',$bday,$mcon);
	$date=date("Y-m-d H:i");
	
	$sql="insert into ".FEEDBACK." (touserid,sub,con,date,msgtype) VALUES ('$_POST[userid]','$_POST[title]','$_POST[body]','$date',3)";
	$db->query($sql);

	$re=send_mail($_POST["email"],$cons["user"],$_POST["title"],$mcon);
	if($re)
		echo "<script>alert('".lang_show('send_ok')."');</script>";
}
//============================
$db->query("select a.email,b.company from ".ALLUSER." a left join ".USER." b on a.userid=b.userid where a.userid='$_GET[userid]'");
$userd=$db->fetchRow();
//============================
if(isset($_GET["modid"]))
{
	$db->query("select * from ".MAILMOD." where id='$_GET[modid]'");
	$mde=$db->fetchRow();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_jumpMenu(v){ //v3.0
  window.location='sendmail.php?userid=<?php echo $_GET['userid'];?>&modid='+v;
}
//-->
</script>
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxbody">
      <form action="" method="post" enctype="multipart/form-data">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="372">
	      <tr>
          <td colspan="2" style=" border-bottom:2px solid #CCCCCC; border-top:none;">
		  <select class="select" name="menu1" onchange="MM_jumpMenu(this.value)">
            <?php
			$db->query("select * from ".MAILMOD." where flag='' or flag is NULL order by id desc");
			$lre=$db->getRows();
			foreach($lre as $v)
			{
			?>
              <option value="<?php echo $v['id'];?>"><?php echo $v["subject"];?></option>
              <?php
			}
			?>
            </select>
			<span class="bz">选择邮件模板，快捷输入。邮件模板可以在邮件模块中进行设置</span>
          </td>
        </tr>
        <tr>
          <td width="14%"><?php echo lang_show('send_to');?>:</td>
          <td width="86%"><?php echo $userd['email']; ?>(<?php echo $userd['company'];?>)
            <input name="email" type="hidden" value="<?php echo $userd['email']; ?>" size="60">
          </td>
        </tr>
        <tr>
          <td width="14%"><?php echo lang_show('mail_subject');?>:</td>
          <td width="86%"><input class="text" name="title" type="text" id="title"  value="<?php if(isset($mde["subject"])) echo $mde["subject"];?>">
          </td>
        </tr>
        <tr>
          <td width="14%" height="262" valign="top"><?php echo lang_show('mail_content');?>:</td>
          <td width="86%" valign="top">
		  <script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
                            
			<script>
            var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name="body"]', {
                    resizeType : 1,
                    allowPreviewEmoticons : false,
                    allowImageUpload : false,
                    langType :'<?php echo $config['language']; ?>',
                });
            });
            </script>
            <textarea name="body" style="width:90%; height:400px;"><?php echo $mde["message"] ?></textarea>
            
		 
          </td>
        </tr>
        <tr>
          <td width="14%">&nbsp;</td>
          <td width="86%"><input name="action" type="hidden" value="send">
            <input name="userid" type="hidden" id="userid" value="<?php echo $_GET["userid"]; ?>">
            <input class="btn" type="submit" name="cc" value="<?php echo lang_show('send_mail');?>">
          </td>
        </tr>
    </table>
      </form>
  </div>
</div>
</body>
</html>
