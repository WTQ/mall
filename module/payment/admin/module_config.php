<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
@include_once("auth.php");
//=========================================
$type='module_payment';
if(!empty($_POST['submit']))
{
	//---------------------------------------初始化账号
	$sql="select * from ".PUSER." where email='$_POST[email]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if(empty($re['email']))
	{
		$sql="insert into ".PUSER." (userid,name,email) value ('0','administrator','admin@systerm.com')";
		$db->query($sql);
	}
	//---------------------------------------
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
	admin_msg("module.php?m=payment&s=module_config.php","设置成功");
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
if(!$reg_config)
{
	@include('config/'.$type.'_config.php');
	$con=$type.'_config';
	$reg_config=$$con;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script src="../script/jquery-1.4.4.min.js" type=text/javascript></script>
</head>
<body>
<div class="bigbox">
  <div class="bigboxhead">模块配置</div>
  <div class="bigboxbody">
    <form name="sysconfig" action="" method="post">
      <table width="100%" cellspacing="0">
        <tr class="theader">
          <td colspan="2">系统账号</td>
        </tr>
        <tr>
          <td class="searh_left" align="left" >说明</td>
          <td align="left" >支付中心采用了独立化模块功能开发，具有和支付宝、财富通相同的功能和工作机制。<br />
            此账号是系统默认网站运营方的账号，用于接收用户在网站上购买广告，升级会员组，购买积分，用户单方面在网站上进行购买网站提供的收费服务，只是用来计算费用而以，不存在任何代表意义。 </td>
        </tr>
        <tr>
          <td align="left" >Email</td>
          <td align="left" ><input class="text" name="email" type="text" id="email" value="admin@systerm.com" size="10">
            <span class="bz">暂时不可以修改，默认admin@systerm.com</span><br /></td>
        </tr>
        <tr>
          <td  align="left" >&nbsp;</td>
          <td align="left" ><input class="btn" type="submit" name="submit" value="提交" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</HTML>
