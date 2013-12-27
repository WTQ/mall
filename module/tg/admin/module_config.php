<?php
//=========================================
$type='module_tg';
if(!empty($_POST['submit']))
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
	admin_msg("module.php?m=tg&s=module_config.php","设置成功");
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
	<form name="sysconfig" action="" method="post" style="margin-top:0px;">
	<table width="100%" cellspacing="0">
	   <tr class="theader">
	     <td colspan="4">权限设置</td>
		<tr>
	     <td>游客</td>
         <td colspan="3">
			<table class="nbr" width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>是否允许浏览此频道
				  <input class="text" style="width:30px;" name="view_0" type="text" maxlength="4" value="<?php echo $reg_config['view_0']; ?>" /><span class="bz">1是，0否</span>
				</td>
				<td>&nbsp;</td>
				 <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>
			</td>
        </tr>
        </tr>
		<?php
		$sql="select * from ".USERGROUP." order by group_id asc";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
		?>
	   <tr>
	     <td><?php echo $v['name'];?></td>
         <td colspan="3">
			<table class="nbr" width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>是否允许浏览此频道
				  <input class="text" style="width:30px;" name="view_<?php echo $v['group_id'];?>" type="text" maxlength="4" value="<?php echo $reg_config['view_'.$v['group_id']]; ?>" /><span class="bz">1是，0否</span>
				</td>
				<td>&nbsp;</td>
				 <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
			</table>
			</td>
        </tr>
		<?php } ?>
		
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
			  <td height="22" align="left"><pre id="line1">频道详情</pre></td>
			  <td align="left" ><input class="text" name="title3" type="text" id="title3" value="<?php echo $reg_config['title3'];?>" size="10" /><br />
			    <span class="bz">[title]标题[catname]类别名</span></td>
			  <td align="left" ><input class="text" name="keyword3" type="text" id="keyword3" size="10" value="<?php echo $reg_config['keyword3'];?>" /><br /><span class="bz">[title]标题[catname]类别名</span></td>
			  <td align="left" ><input class="text" name="description3" type="text" id="description3" size="10" value="<?php echo $reg_config['description3'];?>" /><br /><<span class="bz">[title]标题[catname]类别名</span></td>
			</tr>
        <tr>
          <td width="17%" height="40" align="right">&nbsp;</td>
          <td colspan="3" align="left" ><input  class="btn" type="submit" name="submit" value=" 提交 "></td>
        </tr>
      </table>
	</form>
	</div>
</div>
</body>
</HTML>