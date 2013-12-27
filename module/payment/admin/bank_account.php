<?php
//==========================================
if(!empty($_GET['deid']))
{
	$sql="delete from ".ACCOUNTS." where id='$_GET[deid]'";
	$db->query($sql);
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
<div class="guidebox">
<?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('bank_a');?>
</div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('bankcensor');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
        <tr class="theader">
          <td width="10%">申请人</td>
          <td width="13%"><?php echo lang_show('bank_c');?></td>
          <td width="21%">开户行</td>
          <td width="22%"><?php echo lang_show('bank_d');?></td>
          <td width="8%"><?php echo lang_show('bank_e');?></td>
          <td width="10%"><?php echo lang_show('bank_f');?></td>
          <td width="6%"><?php echo lang_show('bank_g');?></td>
          <td width="10%" align="center" ><?php echo lang_show('bank_h');?></td>
        </tr>
     <?php
	$sql="SELECT
	 		a.id,a.pay_uid,a.bank,a.bank_addr,a.accounts,a.active,a.add_time,a.master,b.name,b.email
	 	FROM 
			".ACCOUNTS." a left join ".PUSER." b on a.pay_uid=b.pay_uid
		WHERE
	  		1 $psql order by a.add_time desc";
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
	//=====================
	$db->query($sql);
	$re=$db->getRows();
	$coun_num=$db->num_rows();
	for($i=0;$i<$coun_num;$i++)
	{ 
	?>
        <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td><?php echo $re[$i]['email']; ?></td>
          <td><?php echo $re[$i]['bank']; ?></td>
          <td><?php echo $re[$i]['bank_addr']; ?></td>
          <td><?php echo $re[$i]['accounts']; ?></td>
		  <td><?php echo $re[$i]['master']; ?></td>
          <td><?php echo dateformat($re[$i]['add_time']); ?></td>
          <td><?php  
			if($re[$i]['active'] ==0)
				echo lang_show('bank_i');
			else if($re[$i]['active'] ==1)
				echo lang_show('bank_j');
		 	?>          </td>
          <td align="center">
		  <a href="module.php?m=payment&s=bank_account_mod.php&id=<?php echo $re[$i]['id']; ?>"><?php echo $editimg;?></a>
		  <?php
			echo ' <a href="module.php?m=payment&s=bank_account.php&deid='.$re[$i]['id'].'">'.$delimg.'</a>';
		  ?>		  </td>
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
