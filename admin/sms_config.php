<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
@include_once("auth.php");
//=========================================
$type='sms';
if($_POST["sub"]=='sms' and !empty($_POST['mob']) and !empty($_POST['con']))
{
	include_once("../includes/plugin_sms_class.php");
	$sms=new sms();
	$str=$sms->send($_POST['mob'],$_POST['con']);
	$str = iconv("gb2312","utf-8//IGNORE",urldecode($str));
	admin_msg($type."_config.php",$str);
	exit;
}

if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='$type'";
		$db->query($sql);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='$type'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','$type')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config($type);//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	$write_config_con_str='<?php $'.$type.'_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/'.$type.'_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg($type."_config.php","设置成功");
	exit;
}
//===读库函数，生成config形式的数组====
function read_config($type)
{
	global $db;
	$sql="select * from ".CONFIG." where type='$type'";
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
$reg_config=read_config($type);
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
	<div class="bigboxhead">短信设置</div>
	<div class="bigboxbody">
	<form name="sysconfig" action="" method="post" style="margin-top:0px;">
	<table width="100%" cellspacing="0">
		<tr>
            <td width="16%" align="left">帐号：</td>
            <td width="84%" align="left">
            <input class="text" name="sms_account" type="text" id="sms_account" value="<?php echo $reg_config['sms_account'];?>"></td>
        </tr>
        <tr>
            <td align="left">密码：</td>
            <td align="left">
            <input class="text" name="sms_pass" type="text" id="sms_pass" value="<?php echo $reg_config['sms_pass'];?>"></td>
        </tr>
               
        <tr>
          <td width="16%" align="right">&nbsp;</td>
          <td width="84%" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
	</form>
	</div>
   </div> 
    <div class="bigbox" style="margin-top:20px;">
	<div class="bigboxhead">短信配置测试</div>
	<div class="bigboxbody">
    <div class="bigboxbody">
    <form name="smsconfig" action="" method="post">
    <input type="hidden" name="sub" value="sms" />
        <table width="100%">
        <tr><td width="16%">手机:</td><td><input type="text" name="mob" class="text" value="" /></td>
        <tr><td>内容:</td><td><textarea name="con" style="width:250px"></textarea></td></tr>
        <tr><td></td><td><input type="submit" class="btn" value="发送" /></td></tr>
        </table>
    </form>
    </div>
</div>
</body>
</HTML>