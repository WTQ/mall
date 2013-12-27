<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
@include_once("../config/reg_config.php");
//=========================================
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	unset($_POST['submit']);
	foreach($_POST as $pname=>$pvalue)
	{
		$sql="select * from ".CONFIG." where `index`='$pname' and type='reg'";
		$db->query($sql);
		if($db->num_rows())
		   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname' and type='reg'";
		else
		   $sql1="insert into ".CONFIG." (`index`,value,type) values ('$pname','$pvalue','reg')";
		$db->query($sql1);
	}
	/****更新缓存文件*********/
	$write_config_con_array=read_config();//从库里取出数据生成数组
	$write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	$write_config_con_str=str_replace("'","\'",$write_config_con_str);
	
	$write_config_con_str='<?php $reg_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/reg_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	admin_msg("reg_config.php","设置成功");
	exit;
}
//===读库函数，生成config形式的数组====
function read_config()
{
	global $db;
	$sql="select * from ".CONFIG." where type='reg'";
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
//===================================
function read_dir($dir)
{
	$i=0;
	$handle = opendir($dir); 
	$rdir=array();
	while ($filename = readdir($handle))
	{ 
		if($filename!="."&&$filename!="..")
		{
		  if(is_dir($dir.$filename))
		  { 
		  	 if(substr($filename,0,5)!='user_'&&substr($filename,0,5)!='email')
		   	 	$rdir[]=$filename;
		  }
	   }
	}
	return $rdir;
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
	<div class="bigboxhead">会员注册设置</div>
	<div class="bigboxbody">
	<form name="sysconfig" action="reg_config.php" method="post" style="margin-top:0px;">
	<table width="100%" cellspacing="0">
         <tr>
          <td class="body_left" align="left" ><?php echo lang_show('openregemail');?></td>
          <td align="left" ><input type="radio" class="radio" name="openregemail" value="1" <?php
		  if ($reg_config['openregemail']==1)
			echo "checked";
		  ?>>
            <?php echo lang_show('apply');?>
  <input type="radio" class="radio" name="openregemail" value="0" <?php
		  if ($reg_config['openregemail']==0)
			echo "checked";
		  ?>>
<?php echo lang_show('forbid');?></td>
        </tr>
         <tr>
           <td align="left"><?php echo lang_show('userregverf');?></span></td>
           <td align="left">
            <input type="radio" class="radio" name="user_reg_verf" value="1" <?php
		  if (!empty($reg_config['user_reg_verf'])&&$reg_config['user_reg_verf']==1)
			echo "checked";
		  ?>>
             <?php echo lang_show('apply');?>
             <input type="radio" class="radio" name="user_reg_verf" value="0" <?php
		  if ($reg_config['user_reg_verf']==0)
			echo "checked";
		  ?>>
           <?php echo lang_show('forbid'); ?>
		   <a href="user_reg_verf.php">[设置问题]</a>
		   </td>
        </tr>
         <tr>
           <td align="left">开启介绍注册</td>
           <td align="left">
		  <input type="radio" class="radio" name="invite" value="1" <?php
		  if (!empty($reg_config['invite'])&&$reg_config['invite']==1)
			echo "checked";
		  ?>>
             <?php echo lang_show('apply');?>
             <input type="radio" class="radio" name="invite" value="0" <?php
		  if ($reg_config['invite']==0)
			echo "checked";
		  ?>>
           <?php echo lang_show('forbid'); ?><br />
		   <span class="bz">开启后，用户在注册成功后会引导填写介绍业务员，自动为其分配客服代表，开启之前要先把业务员信息加入管理员列表</span>
		   </td>
         </tr>
         <tr>
          <td  align="left"><?php echo lang_show('user_reg');?></td>
          <td align="left">
		  <?php
		  $user_reg_array=array('2'=>lang_show('noaudit'),'1'=>lang_show('manaudit'),'3'=>lang_show('mailaudit'));
		  ?>
		  <select class="select" name="user_reg" id="user_reg">
		  <?php foreach($user_reg_array as $key=>$v){?>
              <option value="<?php echo $key;?>" <?php if($reg_config['user_reg']==$key)echo 'selected';?>><?php echo $v;?></option>
		  <?php } ?>
           </select></td>
        </tr>

        <tr>
          <td  align="left" ><?php echo lang_show('openbbs');?></td>
          <td align="left" ><select class="select" name="openbbs" id="select">
            <option value="0" <?php
		  if($reg_config['openbbs']==0 || empty($reg_config['openbbs']))
		  echo "selected";
		  ?>>无</option>
            <option value="2" <?php
		  if($reg_config['openbbs']==2)
		  echo "selected";
		  ?>>UC1.5.0</option>
          </select> <a href="uc_config.php">[整合设置]</a></td>
        </tr>
                <tr>
                  <td align="left"><?php echo lang_show('inhibit_ip');?></td>
                  <td align="left" ><input onClick="$('#exception_ip_div').show();" type="radio" class="radio" name="inhibit_ip" id="inhibit_ip" value="1" <?php
		  if ($reg_config['inhibit_ip']==1)
			echo "checked";
		  ?>>
             <?php echo lang_show('apply');?>
             <input onClick="$('#exception_ip_div').hide();" type="radio" class="radio" name="inhibit_ip" id="inhibit_ip2" value="0" <?php
		  if ($reg_config['inhibit_ip']==0)
			{echo "checked";}
		  ?>>
             <?php echo lang_show('forbid');?>
			 <div id="exception_ip_div" <?php if($reg_config['inhibit_ip']!=1) echo 'style="display:none;"';?> >
             <span class="bz">填加例外，以下IP将不受此限止,每行一个</span><br>
             <textarea name="exception_ip" class="text" rows="5" id="exception_ip"><?php echo $reg_config['exception_ip'];?></textarea>
			 </div>           </td>
                </tr>
        <tr>
          <td align="left"><?php echo lang_show('association');?></td>
          <td align="left" >
          <textarea name="association" id="association" class="text" rows="5"><?php echo $reg_config['association'];?></textarea>          </td>
        </tr>
        <tr>
          <td align="left">注册开关</td>
          <td align="left">
            <select class="select" name="closetype" id="closetype">
              <option <?php if($reg_config['closetype']==0){ echo 'selected';}?> value="0"><?php echo lang_show('openall');?></option>
              <option <?php if($reg_config['closetype']==2){ echo 'selected';}?> value="2"><?php echo lang_show('closereg');?></option>
            </select><br />
            <textarea style="margin-top:5px;" name="closecon" id="closecon" class="text" rows="5"><?php echo $reg_config['closecon'];?></textarea>		  </td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="left" ><input  class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
	</form>
	</div>
</div>
</body>
</HTML>