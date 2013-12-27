<?php
//=========================================
$type='module_brand';
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		if(is_array($pvalue))
			$pvalue=implode(',',$pvalue);
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
	admin_msg("module.php?m=brand&s=module_config.php","设置成功");
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
	     <td colspan="4">SEO设置</td>
	   </tr>
		   <tr>
		     <td  align="left" >&nbsp;</td>
		     <td align="left" >Title</td>
             <td align="left" >Keyword</td>
             <td align="left" >Description</td>
	    </tr>
		   <tr>
			  <td width="17%"  align="left" >频道首页</td>
		      <td align="left" >
			  <input class="text" name="title" type="text" id="title" value="<?php echo $reg_config['title'];?>" size="10"></td>
			 <td align="left" ><input class="text" name="keyword" type="text" id="keyword" size="10" value="<?php echo $reg_config['keyword'];?>" /></td>
			 <td align="left" ><input class="text" name="description" type="text" id="description" size="10" value="<?php echo $reg_config['description'];?>" /></td>
		   </tr>
			<tr>
			  <td height="21" align="left">频道列表</td>
			  <td align="left" ><input class="text" name="title2" type="text" id="title2" value="<?php echo $reg_config['title2'];?>" size="10" />
			  <br />
			  <span class="bz">[catname]类别名</span></td>
			  <td align="left" ><input class="text" name="keyword2" type="text" id="keyword2" size="10" value="<?php echo $reg_config['keyword2'];?>" />
			  <br />
			  <span class="bz">[catname]类别名</span></td>
			  <td align="left" ><input class="text" name="description2" type="text" id="description2" size="10" value="<?php echo $reg_config['description2'];?>" />
			  <br /><span class="bz">[catname]类别名</span></td>
			</tr>
			<tr>
			  <td height="22" align="left"><pre id="line1">频道详情页</pre></td>
			  <td align="left" ><input class="text" name="title3" type="text" id="title3" value="<?php echo $reg_config['title3'];?>" size="10" /><br />
			  <span class="bz">预留</span></td>
			  <td align="left" ><input class="text" name="keyword3" type="text" id="keyword3" size="10" value="<?php echo $reg_config['keyword3'];?>" /><br />
		      <span class="bz">预留</span></td>
			  <td align="left" ><input class="text" name="description3" type="text" id="description3" size="10" value="<?php echo $reg_config['description3'];?>" /><br />
		      <span class="bz">预留</span></td>
			</tr>
            <tr class="theader">
              <td colspan="4" align="left">其它</td>
            </tr>
            <tr>
              <td align="left">编辑器</td>
              <td colspan="3" align="left" ><input class="text" type="text" value="<?php echo $reg_config['editor'];?>" name="editor" /><span class="bz">Default,B2Bbuilder,frant,Basic</span></td>
            </tr>
            <tr>
              <td align="left">分页条数</td>
              <td colspan="3" align="left" ><input class="text" type="text" name="list_row" value="<?php echo $reg_config['list_row'];?>" /></td>
            </tr>
            <tr>
              <td align="left">显示排序</td>
              <td colspan="3" align="left" ><input class="text" type="text" value="<?php echo $reg_config['orderby'];?>" name="orderby" /><span class="bz">Default: b.ifpay desc,a.uptime desc</span></td>
            </tr>
            <tr>
              <td align="left">默认缩略图大小</td>
              <td colspan="3" align="left" ><input value="<?php echo $reg_config['pic_width'];?>" class="ltext" type="text" name="pic_width" /> 
                -- 
                <input value="<?php echo $reg_config['pic_height'];?>" class="rtext" type="text" name="pic_height" />px</td>
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
</HTML>