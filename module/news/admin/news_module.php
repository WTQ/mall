<?php
include_once("../module/".$_GET['m']."/includes/news_function.php");
//=================================================
if(!empty($_GET['del']))
{
	@unlink($config['webroot'].'/templates/default/label/defind_news_'.$_GET['del'].'.htm');
	@unlink($config['webroot'].'/config/label/news_'.$_GET['del'].'.php');
}
if(!empty($_POST['Submit']))
{
	unset($_POST['Submit']);
	$_POST['time']=empty($_POST['time'])?time():$_POST['time'];
	
	if(!empty($_POST['template_con']))
	{
		@$_POST['template_con']=str_replace('\\','',$_POST['template_con']);
		$fp=fopen('../templates/default/label/defind_news_'.$_POST['time'].'.htm','w');
		fwrite($fp,$_POST['template_con'],strlen($_POST['template_con']));//将内容写入文件．
		fclose($fp);
	}
	$_POST['template']='defind_news_'.$_POST['time'].'.htm';
	
	unset($_POST['template_con']);
		if(!empty($_POST['catid']))
	$_POST['catid']=implode(",",$_POST['catid']);
	$write_config_con_str=serialize($_POST);//将数组序列化后生成字符串
	$write_config_con_str='<?php $news_label = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/label/news_'.$_POST['time'].'.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	msg('module.php?m=news&s=news_module.php');
}
if(!empty($_GET['time']))
{
	@include('../config/label/news_'.$_GET['time'].'.php');
	$_GET['catid']=!empty($news_label['catid'])?explode(",",$news_label['catid']):0;
	$fn='../templates/default/label/defind_news_'.$_GET['time'].'.htm';
	@$fp=fopen($fn,'r');
	@$con=fread($fp,filesize($fn));
	@fclose($fp);
}
else
	$news_label=NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('create_news_label');?></div>
  <div class="bigboxbody">
    <form name=form action="" method="post">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><?php echo lang_show('lname');?></td>
          <td><input type="text" name="name" id="name" value="<?php echo $news_label['name'];?>"></td>
          <td width="66%" rowspan="6" valign="top"><?php echo lang_show('rightmsg');?><br>
            <textarea name="template_con" id="template_con" cols="80" rows="20"><?php if(!empty($con)) echo $con;?>
<{foreach item=list name=name from=$news}>
<img src="<{$list.pic}>" />
<a target="_blank" title="<{$list.title}>" <{$list.style}> href="<{$list.url}>"><{$list.ftitle|truncate:30}></a>
<p><{$list.smalltext|strip_tags|truncate:44:"...":true}></p>
<{/foreach}>
    </textarea>
          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('nums');?></td>
          <td><input name="num" type="text" id="num" value="<?php echo $news_label['num'];?>"></td>
        </tr>
        <tr>
          <td width="14%"><?php echo lang_show('fcatid');?></td>
          <td width="20%"><select name="catid[]" size="5" >
              <option value=""><?php echo lang_show('allcatid');?></option>
              <?php
	list_all_cat(0,'option');
	?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo lang_show('fcatid');?></td>
          <td><?php
        $ar_rc_type = array (
            '0'=>lang_show('lasted'),
            'onclick'=>lang_show('recom'),
            'top'=>lang_show('recstar'),
            'rec'=>lang_show('recimp'),
            /* '4'=>lang_show('recflash'),*/
        );
        if(empty($news_label['news_statu']))
            $de['news_statu']=0;
        ?>
            <select name="news_statu">
              <?php foreach($ar_rc_type as $key=>$v) { ?>
              <option value="<?php echo $key; ?>" <?php if($news_label['news_statu']==$key)echo "selected"; ?>><?php echo $v;?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('showuser');?></td>
          <td><input name="from" type="checkbox" class="checkbox" id="from" value="true" <?php if(!empty($news_label['from'])) echo 'checked';?> >
          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('showpic');?></td>
          <td><?php if(!empty($news_label['time'])){ ?>
            <input name="time" type="hidden" value="<?php echo $news_label['time'];?>">
            <?php } ?>
            <input name="image" type="checkbox" class="checkbox" id="image" value="1" <?php if(!empty($news_label['image'])) echo 'checked';?> >
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td colspan="2"><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('madelabel');?>" id="Submit"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div style="float:left; height:20px; width:50%;">&nbsp;</div>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("de[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("de[]")[j].checked==true)
	  	  document.getElementsByName("de[]")[j].checked = false;
		else
		  document.getElementsByName("de[]")[j].checked = true;
	 }
}
</script>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('islabel');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" align="left" cellpadding="2" cellspacing="0" >
      <tr class="theader">
        <td width="170" height="20" ><?php echo lang_show('lname');?></td>
        <td width="380" ><?php echo lang_show('fmod');?></td>
        <td width="231" ><?php echo lang_show('ctime');?></td>
        <td width="191" align="center" ><?php echo lang_show('manager');?></td>
      </tr>
      <?php
	function read_dir($dir)
	{
		$i=0;
		$handle = opendir($dir); 
		$rdir=array();
		while ($filename = readdir($handle))
		{ 
			if($filename!="."&&$filename!="..")
			{
			  if(!is_dir($dir.$filename))
			  { 
					$rdir[]=$filename;
			  }
		   }
		}
		return $rdir;
	}
	$dir=read_dir('../config/label');
	foreach($dir as $v)
	{
		if(substr($v,0,5)=='news_')
		{
			include('../config/label/'.$v);
	?>
      <tr>
        <td><?php echo $news_label['name']; ?></td>
        <td><input name="textfield" type="text" id="textfield" value="<{insert name='label' type='news' labelid='<?php echo $news_label['time'];?>'}>" size="60">
        </td>
        <td><?php echo dateformat($news_label['time']); ?></td>
        <td width="191" align="center"><a href="module.php?m=<?php echo $_GET['m'];?>&s=<?php echo $_GET['s'];?>&time=<?php echo $news_label['time'];?>"><?php echo lang_show('edit');?></a> <a href="module.php?m=<?php echo $_GET['m'];?>&s=<?php echo $_GET['s'];?>&del=<?php echo $news_label['time'];?>"><?php echo lang_show('delete');?></a> </td>
      </tr>
      <?php
	unset($news_label); 
	}}
	?>
    </table>
  </div>
</div>
</body>
</html>
