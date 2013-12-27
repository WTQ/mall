<?php
if($_POST['oid']*1>0&&$_POST['newid']*1>0)
{
	$oid=$_POST['oid']*1;
	$newid=$_POST['newid']*1;
	$s=$oid.'00';
	$b=$oid.'99';
	
	$sql="update ".PRO." set catid='$newid' where catid='$oid'";
	$db->query($sql);
	$sql="update ".PRO." set catid=replace(catid,$oid,$newid) where catid>=$s and catid<=$b";
	$db->query($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead">
		<span class="cbox">产品转移</span>
	</div>
  <div class="bigboxbody">
    <form id="form1" name="form1" method="post" action="">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%">原产品类别号</td>
          <td width="85%"><label><input class="text" type="text" name="oid" /></label></td>
        </tr>
        <tr>
          <td>目的产品类别号</td>
          <td><label><input class="text" type="text" name="newid" /></label></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><label><input class="btn" type="submit" name="Submit" value="提交" /></label></td>
        </tr>
      </table>
        </form>
  </div>
</div>