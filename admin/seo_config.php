<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
@include_once("../config/seo_config.php");
//=========================================入库操作
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='seo'";
		$db->query($sql);
		if($db->num_rows())
		   $sql=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='seo'";
		else
		   $sql="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','seo')";
		$db->query($sql);
	}
	//die;
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	
	$write_config_con_str='<?php $config = array_merge($config, unserialize(\''.$write_config_con_str.'\'));?>';//生成要写的内容
	$fp=fopen('../config/seo_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("seo_config.php","设置成功");
	exit;
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='seo'";
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
$config=read_config();
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
  <div class="bigboxhead">SEO优化设置</div>
  <div class="bigboxbody">
    <form action="" method="post">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="body_left" align="left" ><?php echo lang_show('opensuburl');?><a href="#" title="<?php echo lang_show('opensuburl_des');?>"><img src="../image/admin/Help Circle.jpg" border="0" ></a></td>
          <td width="85%" align="left" ><input type="radio" class="radio" name="opensuburl" id="opensuburl" value="1" <?php
		  if ($config['opensuburl']==1)
			echo "checked";
		  ?>>
            <?php echo lang_show('apply');?>
            <input type="radio" class="radio" name="opensuburl" id="opensuburl2" value="0" <?php
		  if ($config['opensuburl']==0)
			echo "checked";
		  ?>>
            <?php echo lang_show('forbid');?></td>
        </tr>
        <tr>
          <td  align="left"><?php echo lang_show('open_rewrite');?><br>
            (URL Rewrite)<a href="#" title="<?php echo lang_show('rewrite_des');?>"><img src="../image/admin/Help Circle.jpg" border="0" ></a></td>
          <td align="left" ><input type="radio" class="radio" name="rewrite" value="1" <?php
		  if ($config['rewrite']==1)
			echo "checked";
		  ?>>
            <?php echo lang_show('apply');?>
            <input type="radio" class="radio" name="rewrite" value="0" <?php
		  if ($config['rewrite']!=1)
			echo "checked";
		  ?>>
            <?php echo lang_show('forbid');?> </td>
        </tr>
        <tr>
          <td align="left" scope="row">Title(<?php echo lang_show('title');?>)</td>
          <td align="left" ><input name="title" type="text" id="title" class="text" value="<?php echo $config['title'];?>" size="45">
          </td>
        </tr >
        <tr>
          <td  align="left"><?php echo lang_show('keyword');?><br>
            (Meta Keywords)</td>
          <td align="left" ><textarea name="keyword" id="keyword" cols="45" rows="5" class="text" ><?php echo $config['keyword'];?></textarea></td>
        </tr>
        <tr>
          <td  align="left"><?php echo lang_show('description');?><br>
            (Meta Description)</td>
          <td align="left" ><textarea name="description" id="description" cols="45" rows="5" class="text"><?php echo $config['description'];?></textarea>
          </td>
        </tr>
        <tr>
          <td  align="right" scope="row">&nbsp;</td>
          <td align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</HTML>
