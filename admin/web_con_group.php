<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("../lang/" . $config['language'] . "/admin.php");
include_once("auth.php");
//===========================================	
if(isset($_POST['cc']))
{
	foreach($_POST['sort'] as $key=>$val)
	{
		$osql = "update ".WEBCONGROUP." set sort='".intval($val)."' where id='$key'";
		$db->query($osql); 
	}
}

if(isset($_POST['submit']))
{	
	if ($_POST['submit']=='add'&&!empty($_POST['title']))
	{
		$msql="insert into ".WEBCONGROUP." (title,lang,sort,logo) values ('".addslashes($_POST['title'])."','$config[language]',$_POST[sort],'$_POST[logo]')";
		$db->query($msql); 
		admin_msg("web_con_group.php",'操作成功');
	}
}

if(isset($_POST['editID'])&&!empty($_POST['title']))
{	
	$msql="update ".WEBCONGROUP." set title='".addslashes($_POST['title'])."',sort=$_POST[sort],logo='$_POST[logo]'
	 where id=$_POST[editID]";
	$db->query($msql); 
	admin_msg("web_con_group.php",'操作成功');
}

if(isset($_GET['action']))
{
	if($_GET['action']=='del'&&isset($_GET['did']))
	{
		$sql="delete from ".WEBCONGROUP." where id='$_GET[did]'";
		$db->query($sql); 
		$sql ="update ".WEBCON." set con_group='' where con_group='$_GET[did]'";
		$db->query($sql);
	}
}
//============================================
if(isset($_GET['edit_id']))
{
	$sql = "select * from ".WEBCONGROUP." where lang='$config[language]' and id='$_GET[edit_id]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
else
{
	$sql="select * from ".WEBCONGROUP." where lang='$config[language]' order by sort";
	$db->query($sql);
	$de=$db->getRows();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<body>

<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
	<div class="bigboxhead">
		<span class="cbox"><a href="web_con_type.php">管理页面</a></span>
		<span class="cbox"><a href="web_con_type.php?act=add"><?php echo lang_show('webcontype');?></a></span>
		<span class="cbox on"><a href="web_con_group.php"><?php echo lang_show('web_con_type');?></a></span>
	</div>
	<div class="bigboxbody">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
	if(isset($_GET['edit_id'])){
?>
<tr>
<td>
<fieldset style='border:1px dashed #CCCCCC;width:500px;margin-left:30px;padding-bottom:30px;'>
<legend style='margin-left:25px;'>&nbsp;编辑&nbsp;</legend>
<form action='' method='post'>
	<table>
		<tr>
			<td style='border:none;'>分组名</td><td style='border:none;'><input type='text' name='title' class="text" value="<?php echo $de['title']; ?>" /></td>
		</tr>
		<tr>
		  <td style='border:none;'>顺序</td>
		  <td style='border:none;'><input type='text' class="text" name='sort' value=<?php echo $de['sort'];?> maxlength=4 /></td>
	    </tr>
		<tr>
		  <td style='border:none;'>图标</td>
		  <td style='border:none;'><input class="text" name="logo" type="text" id="logo" value="<?php echo $de['logo'];?>" size="30">
		 [<a href="javascript:uploadfile('上传LOGO','logo',27,25,'')">上传</a>] 
		 [<a href="javascript:preview('logo');">预览</a>]
		 [<a onclick="javascript:$('#logo').val('');" href="#">删除</a>]</td>
	    </tr>
		<tr>
			<td style='border:none;'></td><td style='border:none;'>
			<input class="btn" type='submit' value="<?php echo lang_show('submit'); ?>" /> 
			<input type='hidden' name='editID' value='<?php echo $de['id']; ?>' />
			</td>
		</tr>
	</table>
</form>
</fieldset>
</td>
</tr>
<?php }else{ ?>
 <tr><td>
<form method="post" action="" onSubmit="return document.getElementById('title').value!='';">
<fieldset style='border:1px dashed #CCCCCC;width:500px;margin-left:30px;padding-bottom:30px;'>
<legend style='margin-left:25px;'>&nbsp;添加分组&nbsp;</legend>
<table>
		<tr>
			<td style='border:none;'>分组名</td><td style='border:none;'><input type='text' id='title' class="text" maxlength=60 name='title' /></td>
		<tr>
		  <td style='border:none;'>顺序</td>
		  <td style='border:none;'><input type='text'  class="text" name='sort' value=0 maxlength=4 /></td>
	    </tr>
		<tr>
		  <td style='border:none;'>图标</td>
		  <td style='border:none;'>
		  	 <input class="text" name="logo" type="text" id="logo">
			 [<a href="javascript:uploadfile('上传LOGO','logo',27,25,'')">上传</a>] 
			 [<a href="javascript:preview('logo');">预览</a>]
			 [<a onclick="javascript:$('#logo').val('');" href="#">删除</a>]
		  </td>
	    </tr>
		<tr>
			<td style='border:none;'></td><td style='border:none;'>
			<input class="btn"  type='submit' value="<?php echo lang_show('submit'); ?>" /> 
			<input type='hidden' name='submit' value='add' />
			</td>
		</tr>
	</table>
</fieldset>
</form>
</td>
</tr>
<tr>
<td style='padding:0;border-top:none;'>
<form method="post" action="">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr class="theader"><td width=35>顺序</td>
 <td width="164">Logo</td>
 <td width="179"><span style="border:none;">分组名</span></td>
 <td width="628">操作</td>
 </tr>
 <?php foreach($de as $v){ ?>
  <tr>
    <td><input type='text' size=5 maxlength=4 value="<?php echo $v['sort']; ?>" name="sort[<?php echo $v['id']; ?>]" /></td>
    <td><img src="<?php echo $v['logo']; ?>"  /></td>
	<td><?php echo $v['title']; ?></td>
	<td>
	<a href="web_con_group.php?edit_id=<?php echo $v['id']; ?>"><?php echo $editimg;?></a>
    <a href="web_con_group.php?action=del&did=<?php echo $v['id']; ?>" onClick="javascript:return confirm('确定删除？')"><?php echo $delimg; ?></a>
		  </td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan=4><input class="btn" type="submit" name="cc" value="<?php echo lang_show('submit');?>"></td>
  </tr>
</table>
</form>
</td></tr>
<?php } ?>
<table>

</div>
</div>
</body>
</html>
