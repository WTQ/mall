<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
@include_once("../config/home_config.php");
//==============================================
$type='home';
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
	admin_msg("home_config.php","设置成功");
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
<title><?php echo lang_show('admin_system');?></title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
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
          <td height="23" colspan="4" >首页设置</td>
        </tr>
        <tr>
          <td align="left">疯狂抢购</td>
          <td colspan="3" align="left" ><input class="text" name="qanggou" type="text" id="index_catid2" value="<?php echo $reg_config['qanggou'];?>" />
            <span class="bz">填写产品ID号，用,号分隔。如：　1,2,3</span></td>
        </tr>
        <tr>
          <td align="left">热卖产品</td>
          <td colspan="3" align="left" ><input class="text" name="hot_sell" type="text" value="<?php echo $reg_config['hot_sell'];?>" />
            <span class="bz">填写产品ID号，用,号分隔。如：　1,2,3</span></td>
        </tr>
        <tr>
          <td align="left">热评产品</td>
          <td colspan="3" align="left" ><input class="text" name="hot_commen" type="text" id="hot_commen" value="<?php echo $reg_config['hot_commen'];?>" />
            <span class="bz">填写产品ID号，用,号分隔。如：　1,2,3</span></td>
        </tr>
        <tr>
          <td align="left">新品上架</td>
          <td colspan="3" align="left" ><input class="text" name="new_pro" type="text" id="new_pro" value="<?php echo $reg_config['new_pro'];?>" />
            <span class="bz">填写产品ID号，用,号分隔。如：　1,2,3</span></td>
        </tr>
        <tr>
          <td align="left">首页显示楼层</td>
          <td colspan="3" align="left" ><input class="text" name="index_catid" type="text" id="index_catid" value="<?php echo $reg_config['index_catid'];?>" />
            <span class="bz">商城首页显示的楼层，将不同的产品分类ID号以","号分隔填于此处</span></td>
        </tr>
        <tr>
          <td align="left">商城首发</td>
          <td colspan="3" align="left" ><input class="text" name="index_newsid" type="text" id="index_newsid" value="<?php echo $reg_config['index_newsid'];?>" />
            <span class="bz">先在新闻分类里面创建一个用于展示的分类，再将新创建的分类ID填入此处。</span></td>
        </tr>
        <tr>
          <td height="24" align="left">商城列表促销</td>
          <td colspan="3" align="left" ><input class="text" name="list_catid" type="text" id="list_catid" value="<?php echo $reg_config['list_catid'];?>" />
            <span class="bz">先在新闻分类里面创建一个用于展示的分类，再将新创建的分类ID填入此处。</span></td>
        </tr>
        <tr>
          <td width="17%" height="40" align="right">&nbsp;</td>
          <td colspan="3" align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
