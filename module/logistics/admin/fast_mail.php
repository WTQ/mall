<?php

if(!empty($_POST["hraction"])&&$_POST["hraction"]==lang_show('edit'))
{		
	$sql="update ".FASTMAIL." set company='$_POST[company]',introduction='$_POST[introduction]',url='$_POST[url]',logo='$_POST[logo]' where id='$_POST[id]'";
	$db->query($sql);
	msg("module.php?m=logistics&s=fast_mail.php");
	exit();
}
if(!empty($_POST["hraction"])&&$_POST["hraction"]==lang_show('addhr'))
{	
	
	$sql="insert into ".FASTMAIL." (`company`,`introduction`,`url`,`logo`) values ('$_POST[company]','$_POST[introduction]','$_POST[url]','$_POST[logo]')";
	$db->query($sql);
	msg("module.php?m=logistics&s=fast_mail.php");
	exit();
}
//=======================
if(!empty($_GET["action"])&&$_GET["action"]=="del"&&!empty($_GET["id"]))
{
	$db->query("delete from ".FASTMAIL." where id='$_GET[id]'");
}
$sql="select * from ".FASTMAIL."  order by id";
$db->query($sql);
$re=$db->getRows();
if(!empty($_GET['id']))
{
	$sql="select * from ".FASTMAIL." where id='$_GET[id]'";
	$db->query($sql);
	$val=$db->fetchRow();
}
//=======================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../script/my_lightbox.js" language="javascript"></script>
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<form name="form2" method="post">
	<div class="bigbox">
	<div class="bigboxhead">预置物流公司</div>
	<div class="bigboxbody">
	  <table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="searh_left">公司名称</td>
          <td><input class="text" type="text" name="company" value="<?php echo $val['company'];?>" /></td>
        </tr>
        <tr>
          <td>物流公司介绍</td>
          <td><textarea class="text" name="introduction"><?php echo $val['introduction']; ?></textarea></td>
        </tr>
        <tr>
          <td>物流URL接口</td>
          <td><input class="text" type="text" name="url" value="<?php echo $val['url'];?>" /><spa class="bz">卖家发货后可以通过此接口查询货物状态</span></td>
        </tr>
        <tr>
          <td>企业ＬＯＧＯ</td>
          <td><input class="text" type="text" name="logo" value="<?php echo $val['logo'];?>" />[<a href="javascript:uploadfile('上传LOGO','logo',180,60,'')">上传</a>] 
            [<a href="javascript:preview('logo');">预览</a>]
            [<a onclick="javascript:$('#logo').val('');" href="#">删除</a>] </td>
        </tr>
        <tr>
          <td><input type="hidden" name="id" value="<?php if (!empty($_GET["id"])) echo $_GET["id"];?>">&nbsp;</td>
          <td><input class="btn" type="submit" name="hraction" value="<?php 
	  if(!empty($_GET["action"])&&$_GET["action"]=="modify")
	      echo lang_show('edit');
	   else
        echo lang_show('addhr');
	  ?>"></td>
        </tr>
      </table>
	</div>
</div>
</form>
<div style="float:left; height:20px; width:50%;">&nbsp;</div>
<form action="" method="post">
	<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('brandcat');?></div>
	<div class="bigboxbody">

	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="theader">
		  <td width="127">物流公司名</td>
          <td>物流公司介绍</td>
          <td width="300">查询URL接口</td>
          <td width="79" align="center"><?php echo lang_show('actit');?></td>
        </tr>
        <?php
	      foreach ($re as $v)
          {
        ?>
        <tr>
          <td><?php echo $v['company'];?></td>
          <td><?php echo $v['introduction'];?></td>
          <td><?php echo $v['url'];?></td>
          <td align="center" valign="top">
		  <a href="module.php?m=logistics&s=fast_mail.php&action=modify&id=<?php echo $v['id'];?>"><?php echo $editimg;?></a>
		  <a href="module.php?m=logistics&s=fast_mail.php&action=del&id=<?php echo $v['id'];?>"><?php echo $delimg;?></a>		  </td>
        </tr>
		<?php
		  }
		?>
			<tr>
          <td colspan="4" align="left"><input class="btn" type="submit" name="delsel" id="delsel" value="<?php echo lang_show('delete');?>"></td>
        </tr>
      </table>
 </div>
 </div>
</form>
</body>
</html>

