<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead">账户列表</div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" >
      <tr class="theader">
        <td width="5%" >UID</td>
        <td width="22%" >账号</td>
        <td width="17%" align="left" >现金</td>
        <td width="42%" >创建时间</td>
        <td width="14%" align="center" ><?php echo lang_show('pickuplist_g');?></td>
      </tr>
    <?php
	$psql=NULL;
	$sql="SELECT  *  FROM  ".PUSER."  WHERE  1=1  $psql ";
	//=============================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$pages = $page->prompt();
	//=============================
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{ 
	?>
      <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
        <td><?php echo $v["userid"]; ?></td>
        <td align="left"><?php echo $v["email"]; ?></td>
        <td align="left"><?php echo $v["cash"]; ?></td>
        <td align="left"><?php if($v['time'])echo dateformat($v["time"]); ?></td>
        <td align="center"><a  href="module.php?m=payment&s=member_charge.php&act=add&email=<?php echo $v["email"]; ?>">充值</a></td>
        <?php 
    	}
		?>
      </tr>
    </table>
    <div class="page"><?php echo $pages?></div>
  </div>
</div>
</body>
</html>
