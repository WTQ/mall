<?php 
include_once("../includes/global.php");
include_once("../lang/".$config['language']."/mail_box.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
}
.box{width:201px; height:auto; font-size:12px; line-height:20px; color:#205394;}
.box input,textarea{border:1px solid #859DBC; width:120px; margin-left:8px;}
.title{background-image:url(../image/default/titleBg.gif); padding-left:2px; font-weight:bold; padding-top:2px; line-height:normal; height:18px;}
.body{ border-left: 1px solid #859DBC; border-right:1px solid #859DBC; background-color:#EDF6FF; padding-left:4px; padding-top:5px;}
.bottom{background-image:url(../image/default/mbottom.gif); height:5px; background-repeat: no-repeat; line-height:5px;}
.con{height:75px;}
</style>
<script type="text/javascript" src="../script/Validator.js"></script>
</head>
<body>
<script>
function tomin()
{
	if(document.getElementById('fbody').style.display=="none")
	{
		document.getElementById('fbody').style.display="block";
		parent.window.tomax();
	}
	else
	{
		document.getElementById('fbody').style.display="none";
		parent.window.tomin();
	}
}
</script>
<form id="form1" name="form1" method="post" onSubmit="return Validator.Validate(this,1)"
action="../shop.php?uid=<?php echo $_GET['uid'];?>&action=mail&m=company">
<div class="box" id="box">
	<div class="title">
		<span style="float:left; padding-left:3px; padding-top:2px;"><?php echo $lang['msg_bord'];?></span>
		<span id="op" style="float:right; padding-right:5px;" ><img src="../image/default/toMin.gif" onClick="tomin();" /></span>
	</div>
	<div class="body" id="fbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td width="40"><?php echo $lang['title'];?></td>
    <td><input type="text" name="sub" dataType="Require" msg="<?php echo $lang['rtitle'];?>"/></td>
  </tr>
  <tr>
    <td><?php echo $lang['con'];?></td>
    <td><textarea name="con" class="con" dataType="Require" msg="<?php echo $lang['rcon'];?>" ></textarea></td>
  </tr>
  <?php
		if(empty($_COOKIE["USER"]))
		{
		?>
  <tr>
    <td><?php echo $lang['name'];?></td>
    <td><input name="name" type="text" id="name" dataType="Require" msg="<?php echo $lang['rname'];?>" /></td>
  </tr>
  <tr>
    <td><?php echo $lang['email'];?></td>
    <td><input name="email" type="text" id="email" dataType="Require" msg="<?php echo $lang['remail'];?>" /></td>
  </tr>
  <tr>
    <td><?php echo $lang['tel'];?></td>
    <td><input name="tell" type="text" id="tell" /></td>
  </tr>
  <?PHP } else { ?>
  <tr>
      <td><?php echo $lang['sender'];?></td>
      <td><a href="<?php echo $config['weburl'];?>/shop.php?uid=<?PHP echo $buid;?>"><?php echo $_COOKIE["USER"];?></a></td>
  </tr>
  <?php } ?>
    <tr>
      <td><?php echo $lang['yzm'];?></td>
      <td align="left">
      <input dataType="Require" msg="<?php echo $lang['ryzm'];?>" name="yzm" type="text" style="width:40px; height:20px;" />
      <img style="vertical-align:top;" src='../includes/rand_func.php' width="60" height="22"/>
      </td>
   </tr>
    <tr>
     <td></td>
      <td>
        <?php 
		if(isset($_GET["type"]))
		{
			if($_GET["type"]==1)
				echo '<span style="color:#FF0000; font-weight:bold">'.$lang['suc'].'</span>';
			if($_GET["type"]==2)
				echo '<span style="color:#FF0000; font-weight:bold">'.$lang['er_yzm'].'</span>';
		}
		?>
		 <input style="width:60px; height:22px; border:none" type="submit" name="imageField" value="<?php echo $lang['send'];?>" />
		 <input name="toid" type="hidden"  value="<?php echo $_GET['uid'];?>" />
		 <input name="submit" type="hidden" id="submit" value="submit" />
		 <input name="isflow" type="hidden" id="isflow" value="1" />
         <td>
   </tr>
</table>
	</div>
	<div class="bottom"></div>
</div>
</form>
</body>
</html>