<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//===========================================

if(!empty($_POST['con_linkaddr']))
	$ssql=" , con_linkaddr='$_POST[con_linkaddr]'";
	
if(isset($_POST["cc"]))
{
	unset($sql);
	$_POST['msg_online']*=1;
	$sql=" update ".WEBCON." SET 
	title='$_POST[title]',keywords='$_POST[keywords]',description='$_POST[description]',
	con_desc='$_POST[con_desc]',template='$_POST[template]',msg_online='$_POST[msg_online]' $ssql 
	where con_id='$_POST[con_id]' $tsql";	
	$re=$db->query($sql);
	if($re)
		admin_msg("web_con_set.php?con_id=$_GET[con_id]",'操作成功');
}
//============================================
if(empty($_GET['con_id']))
	$_GET["con_id"]=1;
$sql="select * from ".WEBCON." WHERE con_id='$_GET[con_id]'";
$db->query($sql);
$de=$db->fetchRow();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<body>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('about_us');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('about_content_setting');?></div>
<div class="bigboxbody">
<script>
function goto(v)
{
	window.location='?con_id='+v;
}
</script>
<form method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="body_left"><?php echo lang_show('itype');?></td>
    <td width="85%">
	<select name="con_id" onChange="goto(this.value);">
	<?php 
	$sql="select con_id,con_title from ".WEBCON." WHERE 1";
	$db->query($sql);
	$t=$db->getRows();
	foreach($t as $v)
	{
		if($v['con_id']==$de["con_id"])
			echo '<option value='.$v['con_id'].' selected >'.$v['con_title'].'</option>';
		else
			echo '<option value='.$v['con_id'].' >'.$v['con_title'].'</option>';
	}
	?>
    </select>
	</td>
  </tr>
  <tr>
    <td>SEO Title</td>
    <td><input name="title" type="text" id="title" size="80" value="<?php if(!empty($de['title']))echo $de['title'];?>"></td>
  </tr>
  <tr>
    <td>SEO keywords </td>
    <td><input name="keywords" type="text" size="80" value="<?php if(!empty($de['keywords']))echo $de['keywords'];?>"></td>
  </tr>
  <tr>
    <td>SEO description</td>
    <td><input name="description" type="text" size="80" value="<?php if(!empty($de['description']))echo $de['description'];?>"></td>
  </tr>
    <tr>
    <td>Template</td>
    <td>
      <input name="template" type="text" value="<?php if(!empty($de['template']))echo $de['template'];?>">    </td>
  </tr>
  <tr>
    <td><?php echo lang_show('con');?></td>
    <td>
    <script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
                            
	<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="con_desc"]', {
            resizeType : 1,
            allowPreviewEmoticons : false,
            allowImageUpload : false,
            langType :'<?php echo $config['language']; ?>',
        });
    });
    </script>
    <textarea name="con_desc" style="width:90%; height:400px;"><?php echo $de["con_desc"] ?></textarea>
   
	</td>
  </tr>
  <tr>
    <td>调用留言板</td>
    <td>
      <input name="msg_online" type="checkbox" class="checkbox" <?php if($de['msg_online']=='1')echo 'checked';?> value="1">    </td>
  </tr>
  <tr>
    <td>调用网址</td>
    <td>
    <?php if($de['con_linkaddr']){ ?>
    <input size="60" name="con_linkaddr" type="text" value="<?php if($de['con_linkaddr']) echo $de['con_linkaddr'];?>">
    <?php } else {echo "$config[weburl]/aboutus.php?type=".$de['con_id']; } ?>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="btn" type="submit" name="cc" value="<?php echo lang_show('submit');?>"></td>
  </tr>
</table>
</form>
</div>
</div>
</body>
</html>
