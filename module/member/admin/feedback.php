<?php
include_once("../includes/page_utf_class.php");
//=============================
if(!empty($_GET["step"])&&$_GET["step"]=="del")
{
	$db->query("delete from ".FEED." where id='$_GET[id]'");
}
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
	<div class="bigboxhead"><?php echo lang_show('feedback_manager');?></div>
	<div class="bigboxbody">
  <table width="100%" border="0" cellpadding="4" cellspacing="0" >
    <tr class="theader"> 
      <td width="342" align="left" ><?php echo lang_show('company_name');?></td>
      <td width="162" align="center" ><?php echo lang_show('contact');?></td>
      <td width="177" align="center" ><?php echo lang_show('mail');?></td>
      <td width="145" align="center" ><?php echo lang_show('status');?></td>
      <td width="110" align="center" ><?php echo lang_show('query_reply');?></td>
      </tr>
    <?php
	
	if($_GET['noread']==1)
		$tsql.=" and iflook='0'";
		
	$sql="select * from ".FEED." where 1 $tsql order by id desc";
	$db->query($sql);
	$re=$db->getRows();
	for($i=0;$i<count($re);$i++)
	{
		$iflook=$re[$i]['iflook'];
		if($iflook=="0")
		{
			$say=lang_show('nolook');
		}
		elseif($iflook=="1")
		{
			$say=lang_show('noreplay');
		}elseif($iflook=="2")
		{
			$say=lang_show('isreplay');
		}
	?> 
    <tr> 
      <td width="342"> <?php echo $re[$i]['company']; ?> </td>
      <td width="162" align="center"><?php echo $re[$i]['contact']; ?> </td>
      <td width="177" align="center"><?php echo $re[$i]['email']; ?> </td>
      <td width="145" align="center"><?php echo "$say"; ?></td>
      <td align="center">
	  <a href="?m=member&s=feedbackd.php&id=<?php echo $re[$i]['id']; ?>"><?php echo lang_show('query_reply');?></a>
	  | <a href="?m=member&s=feedback.php&step=del&id=<?php echo $re[$i]['id']; ?>"><?php echo lang_show('delete');?></a>
	  </td>
      </tr>
    <?php 
	}
	?> 
</table>
</div>
</div>
</body>
</html>