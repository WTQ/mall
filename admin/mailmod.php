<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//==========================================
if(!empty($_POST["action"])&&$_POST['action']=="add")
{
	if(!empty($_GET["id"]))
		$sql="update ".MAILMOD." set message='$_POST[con]',subject='$_POST[subject]',title='$_POST[title]' 
			where id='$_GET[id]' ";
	else
		$sql="insert into ".MAILMOD." (subject,message,title) values ('$_POST[subject]','$_POST[con]','$_POST[title]')";
	$db->query($sql);
	msg("mailmod.php");
}

if(!empty($_GET["id"])&&$_GET["action"]=="del")
{
	$db->query("delete from ".MAILMOD." where id='$_GET[id]' ");
	msg("mailmod.php");
}
if(!empty($_GET["id"])&&$_GET["action"]=="edit")
{
	$sql="SELECT * FROM ".MAILMOD." WHERE id=$_GET[id]";
	$db->query($sql);
	$detail=$db->fetchRow();
	if(!empty($_GET["type"])&&$_GET["type"]=="view")
	{
		echo $detail["message"];
		die;
	}
}
else
{
	$detail=NULL;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<script language="javascript">
function openwin(txt)
{
	var win = window.open("", "win", "width=900,height=800"); // a window object
	win.document.open("text/html", "replace");
	win.document.write(txt);
	win.document.close();
}
</script>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('mail_tpl');?></div>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('tpl_list');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr class="theader">
        <td >标记</td>
        <td ><?php echo lang_show('mail_name');?></td>
        <td ><?php echo lang_show('mail_title');?></td>
        <td ><?php echo lang_show('operate');?></td>
      </tr>
      <?php
	$sql="select id,subject,title,flag from ".MAILMOD." order by id desc";
	$db->query($sql);
	$re=$db->getRows();
	$num=$db->num_rows();
	if(is_array($re))
	{
		foreach($re as $v)
		{
		?>
      <tr>
        <td width="126"><?php echo $v["flag"];?></td>
        <td width="229"><?php echo $v["title"];?></td>
        <td width="455"><?php echo $v["subject"];?></td>
        <td width="195"><a href="#" onClick="window.open('mailmod.php?id=<?php echo $v["id"]; ?>&action=edit&type=view', 'win', 'width=900,height=800')"><?php echo lang_show('review');?></a> <a href="?id=<?php echo $v["id"]; ?>&action=edit"><?php echo $editimg;?></a>
          <?php if(empty($v['flag'])){ ?>
          <a href="?id=<?php echo $v["id"]; ?>&action=del" onClick="return confirm('<?php echo lang_show('suredel');?>');"><?php echo  $delimg;?></a>
          <?php } ?>
        </td>
      </tr>
      <?php 
		}
	}
	?>
    </table>
  </div>
</div>
<div style="float:left; height:20px; width:80%;">&nbsp;</div>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('add_tpl');?></div>
  <div class="bigboxbody">
      <form name="form1" method="post" action="">
    <table width="100%">
<tr>
<td><?php echo lang_show('tpl_name');?></td>
<td>
<input name="title" type="text" id="title" value="<?php echo $detail["title"]; ?>" size="50">
</td>
</tr>

<tr>
<td><?php echo lang_show('mail_title');?></td>
<td width="85%">
<input name="subject" type="text" id="message" value="<?php echo $detail["subject"]; ?>" size="50">
</td>
</tr>

<tr>
<td>
<?php echo lang_show('mail_content');?>
</td>
<td>
<script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
editor = K.create('textarea[name="con"]', {
resizeType : 1,
allowPreviewEmoticons : false,
allowImageUpload : false,
langType :'<?php echo $config['language']; ?>',
});
});
</script>
<textarea name="con" style="width:90%; height:400px;"><?php echo $detail["message"] ?></textarea>
</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td><input name="cc" class="btn" type="submit" id="cc" value="<?php echo lang_show('confirm');?>">
    <input name="action" type="hidden" value="add">
    <input class="btn" name="" type="button" value="<?php echo lang_show('review');?>" onClick="openwin(con.value)">
    </td>
</tr>
    </table>
    
      </form>
  </div>
</div>
</body>
</html>
