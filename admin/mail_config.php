<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);

@include_once("auth.php");
@include_once("../config/mail_config.php");
//=========================================
if(!empty($_GET['email']))
{
	$res=send_mail($_GET['email'],'test','test','test');
	if($res==1)
		admin_msg("mail_config.php",'成功');
	else
		admin_msg("mail_config.php",'失败');
}
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='mail'";
		$db->query($sql);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='mail'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','mail')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	
	$write_config_con_str='<?php $mail_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/mail_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("mail_config.php",'设置成功');
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='mail'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		$index=$v['index'];
		$value=$v['value'];
		$configs[$index]=$value;
	}
	return $configs;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('sysconfig');?></div>
<div class="bigbox">
  <div class="bigboxhead">邮件配置</div>
  <div class="bigboxbody">
    <form name="sysconfig" action="mail_config.php" method="post" style="margin-top:0px;">
      <table width="100%" cellspacing="0">
        <tr>
          <td colspan="4" align="left">邮件总开关
            <input type="radio" class="radio" name="mail_statu" value="1" <?php
		  if ($mail_config['mail_statu']==1)
			echo "checked";
		  ?>>
            开启
            <input type="radio" class="radio" name="mail_statu" value="0" <?php
		  if ($mail_config['mail_statu']==0)
			echo "checked";
		  ?>>
            关闭<br />
            邮件发送方式
            <input <?php if ($mail_config['sent_type']==1)
			echo "checked";
		  ?> type="radio" class="radio" name="sent_type" value="1" />
            内置mail
            <input <?php if ($mail_config['sent_type']==2)
			echo "checked";
		  ?> type="radio" class="radio" name="sent_type" value="2" />
            以下Smtp</td>
        </tr>
        <tr class="theader">
          <td >&nbsp;</td>
          <td >SMTP地址</td>
          <td >E-mail</td>
          <td >邮箱密码</td>
        </tr>
        <tr>
          <td width="2%" align="left" >1.</td>
          <td width="27%" align="left" ><input name="smtp1" type="text" id="smtp" class="text" maxlength="60" value="<?php echo $mail_config['smtp1'];?>"></td>
          <td width="28%" align="left" ><input name="email1" type="text" id="email" class="text" maxlength="60"	value="<?php echo $mail_config['email1'];?>" /></td>
          <td width="43%" align="left" ><input name="emailPass1" type="password" id="emailpass" class="text" maxlength="60" value="<?php echo $mail_config['emailPass1'];?>"/></td>
        </tr>
        <tr>
          <td width="2%" align="left" >2.</td>
          <td align="left" ><input name="smtp2" type="text" id="smtp2" class="text" maxlength="60" value="<?php echo $mail_config['smtp2'];?>" /></td>
          <td align="left" ><input name="email2" type="text" id="email2" class="text" maxlength="60"	value="<?php echo $mail_config['email2'];?>" /></td>
          <td align="left" ><input name="emailPass2" type="password" id="emailPass2" class="text" maxlength="60" value="<?php echo $mail_config['emailPass2'];?>"/></td>
        </tr>
        <tr>
          <td width="2%" align="left">3.</td>
          <td align="left"><input name="smtp3" type="text" id="smtp3" class="text" maxlength="60" value="<?php echo $mail_config['smtp3'];?>" /></td>
          <td align="left"><input name="email3" type="text" id="email3" class="text" maxlength="60"	value="<?php echo $mail_config['email3'];?>" /></td>
          <td align="left"><input name="emailPass3" type="password" id="emailPass3" class="text" maxlength="60" value="<?php echo $mail_config['emailPass3'];?>"/></td>
        </tr>
        <tr>
          <td height="32" align="left">4.</td>
          <td align="left" ><input name="smtp4" type="text" id="smtp4" class="text" maxlength="60" value="<?php echo $mail_config['smtp4'];?>" /></td>
          <td align="left" ><input name="email4" type="text" id="email4" class="text" maxlength="60"	value="<?php echo $mail_config['email4'];?>" /></td>
          <td align="left" ><input name="emailPass4" type="password" id="emailPass4" class="text" maxlength="60" value="<?php echo $mail_config['emailPass4'];?>"/></td>
        </tr>
        <tr>
          <td height="34" align="left">5.</td>
          <td align="left" ><input name="smtp5" type="text" id="smtp5" class="text" maxlength="60" value="<?php echo $mail_config['smtp5'];?>" /></td>
          <td align="left" ><input name="email5" type="text" id="email5" class="text" maxlength="60"	value="<?php echo $mail_config['email5'];?>" /></td>
          <td align="left" ><input name="emailPass5" type="password" id="emailPass5" class="text" maxlength="60" value="<?php echo $mail_config['emailPass5'];?>"/></td>
        </tr>
        <tr>
          <td height="32" align="left">6.</td>
          <td align="left" ><input name="smtp6" type="text" id="smtp6" class="text" maxlength="60" value="<?php echo $mail_config['smtp6'];?>" /></td>
          <td align="left" ><input name="email6" type="text" id="email6" class="text" maxlength="60"	value="<?php echo $mail_config['email6'];?>" /></td>
          <td align="left" ><input name="emailPass6" type="password" id="emailPass6" class="text" maxlength="60" value="<?php echo $mail_config['emailPass6'];?>"/></td>
        </tr>
        <tr>
          <td width="2%" height="40" align="right">&nbsp;</td>
          <td colspan="3" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>">
            <span class="bz">如果采用smtp方式发送邮件，至少需要填写一个邮箱配置，填写多个会随机选用一个来发送邮件，目的是避免发送垃圾邮件</span> </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div class="bigbox" style="margin-top:10px;">
  <div class="bigboxhead">邮箱配置测试</div>
  <div class="bigboxbody">
    <form name="sysconfig" action="" method="get">
      <table width="100%" cellspacing="0">
        <tr>
          <td width="11%" align="left" >测试收件人Email</td>
          <td width="89%" align="left" ><input name="email" type="text" id="email" class="text" maxlength="60" /></td>
        </tr>
        <tr>
          <td width="11%" height="40" align="right">&nbsp;</td>
          <td width="89%" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('send');?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</HTML>
