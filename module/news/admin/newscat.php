<?php
include_once("../module/".$_GET['m']."/includes/news_function.php");
//==================================================
if(empty($_POST["action"]))
	$_POST["action"]=NULL;
if(empty($_GET["del"]))
	$_GET["del"]=NULL;
$ishome=empty($_POST['ishome'])?0:$_POST['ishome']*1;
$_POST['openpost']=empty($_POST['openpost'])?0:$_POST['openpost']*1;
if($_POST["action"]=="update")
{
	for($i=0;$i<count($_POST["nums"]);$i++)
	{
		if(!empty($_POST["nums"][$i])&&$_POST["nums"][$i]!=''&&is_numeric($_POST["nums"][$i]))
		   $sql="update ".NEWSCAT." set nums='".$_POST["nums"][$i]."' where catid='".$_POST["updateID"][$i]."'";
		else
			$sql="update ".NEWSCAT." set nums='0' where catid='".$_POST["updateID"][$i]."'";
		$db->query($sql);
	}
}

if($_POST["action"]=="submit")
{
	$sql="insert into ".NEWSCAT." (cat,pid,ishome,template,openpost,pic) values 
	('$_POST[cat]','$_POST[pid]','$ishome','$_POST[template]','$_POST[openpost]','$_POST[pic]')";
	$db->query($sql);
}

if($_POST["action"]=="edit")
{
	$sql="update ".NEWSCAT." set cat='$_POST[cat]',pid='$_POST[pid]',ishome='$ishome',template='$_POST[template]',openpost='$_POST[openpost]',pic='$_POST[pic]'
	 where catid='$_POST[catid]'";
	$db->query($sql);
	msg("module.php?m=news&s=newscat.php");
}
//=======================
if(!empty($_GET["del"]))
{
	$sql="SELECT * FROM ".NEWSCAT." WHERE pid='$_GET[del]'";
	$db->query($sql);
	if($db->fetchField("catid"))
		echo "<script>alert('".lang_show('issubmsg')."');window.location='module.php?m=news&s=newscat.php';</script>";
	else
	{
		$cid=get_lowerid($_GET['del']);
		$sql="SELECT nid FROM ".NEWSD." WHERE classid in ($cid)";
		$db->query($sql);
		if($db->fetchField("nid"))
			echo "<script>alert('".lang_show('becon')."');window.location='module.php?m=news&s=newscat.php';</script>";
		else
			$db->query("delete from ".NEWSCAT." where catid='$_GET[del]'");
	}
	msg('module.php?m=news&s=newscat.php');
}
if(!empty($_GET['edit']))
{
	$sql="SELECT * FROM ".NEWSCAT." WHERE catid='$_GET[edit]'";
	$db->query($sql);
	$de=$db->fetchRow();
	if(!empty($de['pid']))
		$_GET['catid']=$de['pid'];
}
//===============================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>

<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
</HEAD>
<body>
<!--<div class="guidebox"><?php echo lang_show('system_setting_home');?>&raquo; <?php echo lang_show('article_type');?></div>-->
<script type="text/javascript" src="../script/Validator.js"></script>
<form onSubmit="return Validator.Validate(this,4)" name="form2" method="post">
	<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('add_type');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  class="searh_left"><?php echo lang_show('catrank');?></td>
          <td width="*%"><select class="select" name="pid" id="pid">
            <option value="0"><?php echo lang_show('type_level_1');?></option>
            <?php list_all_cat(0,'option');?>
          </select></td>
        </tr>
        <tr>
          <td><?php echo lang_show('catname');?></td>
          <td><input class="text" dataType="Require" msg="<img src='../image/default/no_right.gif'>" name="cat" type="text" id="cat" value="<?php echo $de['cat']; ?>"></td>
        </tr>
        <tr>
          <td>Logo</td>
          <td>
             <input class="text" value="<?php echo $de['pic']; ?>" name="pic" type="text" id="pic">
			 [<a href="javascript:uploadfile('上传LOGO','pic',85,32,'news')">上传</a>] 
			 [<a href="javascript:preview('pic');">预览</a>]
			 [<a onclick="javascript:$('#pic').val('');" href="#">删除</a>]
          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('template');?><a title="<?php echo lang_show('templatedes');?>" href="#">?</a></td>
          <td><input class="text" type="text" name="template" id="template" value="<?php echo $de['template']; ?>"></td>
        </tr>
        <tr>
          <td><?php echo lang_show('openpost');?><a title="<?php echo lang_show('openpostdes');?>" href="#">?</a></td>
          <td><input type="checkbox" class="checkbox" name="openpost" id="openpost" value="1" <?php if(!empty($de['openpost'])&&$de['openpost']==1) echo "checked"; ?>>          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('show');?><a title="<?php echo lang_show('showdes');?>" href="#">?</a></td>
          <td><input name="ishome" type="checkbox" class="checkbox" value="1" <?php if(!empty($_GET['ishome'])&&$_GET['ishome']==1) echo "checked"; ?> ></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>">
            <input name="action" type="hidden" id="action" value="<?php if(!empty($_GET["edit"])) echo "edit";else echo "submit";?>" >
            <input name="catid" type="hidden" id="catid" value="<?php echo empty($_GET["edit"])?NULL:$_GET["edit"]; ?>" ></td>
        </tr>
      </table>
	</div>
</div>
</form>

<div style="float:left; height:20px; width:50%;">&nbsp;</div>
<form action="" method="post">
	<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('type_list');?></div>
	<div class="bigboxbody">

	<div style="text-align:left; padding:8px;">
			<ul style="padding-left:40px;">
				<?php list_all_cat(0);?>
			</ul>
	 </div>
	<tr bgcolor="#F0F0F0">
	<td height="23" align="left" valign="middle">
	<input class="btn" type="submit" name="Submit2" value="<?php echo lang_show('mod_sort');?>">
	<input name="action" type="hidden" id="action" value="update">
	</td>
    </tr>
  </table>
 </div>
 </div>
</form>
</body>
</html>