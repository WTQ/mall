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
<form method="get" action="">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('pickuplist_desc');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
        <tr class="theader">
          <td width="21%" >姓名</td>
          <td width="20%" ><span >账号</span></td>
          <td width="21%" align="left" ><?php echo lang_show('pickuplist_d');?></td>
          <td width="18%" ><?php echo lang_show('pickuplist_e');?></td>
          <td width="10%" ><?php echo lang_show('pickuplist_f');?></td>
          <td width="10%" align="center" ><?php echo lang_show('pickuplist_g');?></td>
        </tr>
    <?php
	$sql="SELECT a.*,b.name,b.email FROM ".CASHPICKUP." a left join ".PUSER." b on a.pay_uid=b.pay_uid
		WHERE 1 $psql order by a.add_time desc";
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
	$coun_num=$db->num_rows();
	for($i=0;$i<$coun_num;$i++)
	{ 
	?>
        <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td><?php echo $re[$i]["name"]; ?></td>
          <td align="left"><?php echo $re[$i]["email"]; ?></td>
          <td align="left"><?php echo $re[$i]["amount"]; ?></td>
          <td align="left"><?php echo dateformat($re[$i]["add_time"]); ?></td>
          <td>
		  <?php  
			if($re[$i]["is_succeed"] ==0)
				echo lang_show('pickuplist_h');
			else if($re[$i]["is_succeed"] ==1)
				echo lang_show('pickuplist_i');
			else  if($re[$i]["is_succeed"] ==2)
				echo lang_show('pickuplist_j');
			if(!empty($re[$i]['censor']))
				echo '('.$re[$i]['censor'].')';
		 ?>
          </td>
          <td align="center"><a href="module.php?m=payment&s=pickupmod.php&id=<?php echo $re[$i]["id"]; ?>"><?php echo $editimg;?></a></td>
          <?php 
    	}
		?>
        </tr>
      </table>
	  <div class="page"><?php echo $pages?></div>
	</div>
</div>
</form>
</body>
</html>
