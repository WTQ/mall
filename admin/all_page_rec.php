<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<body>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
  <div class="bigboxhead">
    <?php
	if(!empty($_GET['deid']))
	{
		$sql="delete from ".PAGEREC." where id='$_GET[deid]'";
		$db->query($sql);
	}
	$sql="select * from ".PAGEREC." order by time desc";
	$db->query($sql);
	$Num=$db->num_rows();
	$list=$db->getRows();
	?>
    <?php echo lang_show('trec');?><?php echo $Num?><?php echo lang_show('tdot');?>
   </div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="1" cellspacing="0">
      <tr class="theader">
        <td width="4%" align="left" ><?php echo lang_show('ttime');?></td>
        <td width="32%" align="left" ><?php echo lang_show('tpurls');?></td>
        <td width="9%" align="left" ><?php echo lang_show('turls');?></td>
        <td width="8%" align="left" ><?php echo lang_show('tpvs');?></td>
        <td width="9%" align="left" ><?php echo lang_show('tips');?></td>
        <td width="10%" align="left" ><?php echo lang_show('tusers');?></td>
        <td width="9%" align="left" ><?php echo lang_show('tregusers');?></td>
        <td width="7%" align="left" ><?php echo lang_show('tpros');?></td>
        <td width="6%" align="left" ><?php echo lang_show('tnews');?></td>
        <td width="6%" align="left" >操作</td>
      </tr>
      <?php
	if(is_array($list))
	{
		foreach($list as $rec)
		{
		?>
      <tr>
        <td align="left"><?php echo date('Y-m-d',strtotime($rec['time'])-3600*24);?></td>
        <td align="left"><?php echo urldecode($rec['mostpopularurl']);?></td>
        <td align="left"><b><?php echo urldecode($rec['totalurl']);?></b></td>
        <td align="left"><?php echo $rec['pageviews'];?></td>
        <td align="left"><?php echo $rec['totalip'];?></td>
        <td align="left"><?php echo $rec['visitusernum'];?></td>
        <td align="left"><?php echo $rec['reguser'];?></td>
        <td align="left"><?php echo $rec['pronum'];?></td>
        <td align="left"><?php echo $rec['newsnum'];?></td>
        <td align="left"><a onclick="return confirm('确定删除？');" href="all_page_rec.php?deid=<?php echo $rec['id'];?>"><?php echo $delimg;?></a></td>
      </tr>
      <?php
			 }
		 }
		?>
    </table>
  </div>
</div>
</body>
</html>
