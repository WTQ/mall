<?php
if(!empty($_POST['submit']))
{
	$ar=explode("\r\n",$_POST['name']);
	$com=implode("','",$ar);
	
	$sql="select userid from ".USER." where company in ('$com')";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		if($v['userid']>0)
			$uid[]=$v['userid'];
	}
	$agent=implode(",",$uid);
	$sql="update ".BRAND." set agent_id='$agent' where id='$_GET[id]'";
	$db->query($sql);
	admin_msg('module.php?m=brand&s=brand_list.php','发布成功');
}
if(!empty($_GET['id']))
{
	$sql="select * from ".BRAND." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	
	if(!empty($de['agent_id']))
	{
		$sql="select company from ".USER." where userid in ($de[agent_id])";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			$com_list[]=$v['company'];
		}
		$com_list=implode("\r\n",$com_list);
	}
}
//-------------------------------------
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
	<div class="bigboxhead">设置代理商</div>
    <div class="bigboxbody">
 <form action="" method="post" enctype="multipart/form-data">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">品牌名</td>
    <td><?php echo $de['name'];?></td>
  </tr>
  <tr>
    <td width="119" align="left">代理商公司名</td>
    <td width="773"><textarea name="name" cols="50" rows="20"><?php echo $com_list;?></textarea>
      <br />
      注：每行一个，且已经是本注站册会员</td>
    </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td><input class="btn" type="submit" name="submit" id="button" value="<?php echo lang_show('submit');?>"></td>
    </tr>
</table>
</form>
</div>
</div>
</body>
</html>