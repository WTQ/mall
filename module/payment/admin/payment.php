<?php
include_once($config['webroot']."/module/payment/lang/payment_$langs.php");
//==========================================
if(isset($_GET['step']) && $_GET['step']=='unset')
{
	$sql = "delete from ".PAYMENT." where payment_type='$_GET[payment_type]'";
	$db->query($sql);
}
function load_modules($modules_dir = '')
{
	if(!$modules_dir) {
		return false;
	}
	$target_dir = @opendir($modules_dir);
	$modules_loading = true;
	$modules_list = array();

	while(($module = @readdir($target_dir)) !== false)
	{
		if (preg_match("/^.*?\.php$/", $module)) {
			include_once($modules_dir.'/'.$module);
		}
	}
	@closedir($target_dir);
	return $modules_list;
}
?>
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
  <form action="" method="get">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('payment_set');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
        <tr class="theader">
          <td width="10%" ><?php echo lang_show('payment_h');?></td>
          <td ><?php echo lang_show('payment_b');?></td>
          <td width="14%" align="center" ><?php echo lang_show('payment_d');?></td>
          <td width="11%" align="center" ><?php echo lang_show('payment_e');?></td>
        </tr>
        <?php
	$payments = load_modules('../module/payment/admin/payment');
	$coun_num = count($payments);
	for($i=0;$i<$coun_num;$i++)
	{
		$sql = "select * from ".PAYMENT." where payment_type='".$payments[$i]["payment_name"]."'";
		$db->query($sql);
		$payment_one = $db->fetchRow();
		if ($payment_one) {
			$name = $payment_one['payment_name'];
			$desc = $payment_one['payment_desc'];
			$commission = $payment_one['payment_commission'];
			$active = $payment_one['active'];
		} else {
			$name = lang_show($payments[$i]["payment_name"]);
			$commission = $payments[$i]["payment_commission"];
			$desc = lang_show($payments[$i]["payment_name"].'_desc');
			$active = 0;
		}
	?>
        <tr  onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td><?php echo $name; ?></td>
          <td align="left"><?php echo $desc; ?></td>
          <td align="center"><?php echo $commission; ?></td>
          <td align="center">
		  <?php
			if($active==0) {
			;
			} else {
				echo '<a href="module.php?m=payment&s=paymentmod.php&step=edit&payment_type='.$payments[$i]["payment_name"].'">'.$editimg.'</a>';
			}
		?>		  
		  		  <?php
			if($active==0) {
				echo '<a href="module.php?m=payment&s=paymentmod.php&step=active&payment_type='.$payments[$i]["payment_name"].'">'.$stopimg.'</a>';
			} else {
				echo '<a onClick="return confirm(\''.lang_show('are_you_sure').'\')" href="module.php?m=payment&s=payment.php&step=unset&payment_type='.$payments[$i]["payment_name"].'">'.$startimg.'</a> ';
			}
		?>		  </td>
          <?php 
    	}
		?>
        </tr>
      </table>
	</div>
</div>
</form>
</body>
</html>
