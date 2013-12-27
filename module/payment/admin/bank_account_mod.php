<?php
if(isset($_POST["action"]))
{
	if(isset($_POST["result"]))
	{
		$add_time = time();
		if($_POST["result"]==1)
		{
			$sql = "update ".ACCOUNTS." set active=1, check_time='$add_time', censor='$_SESSION[ADMIN_USER]' where id=$_POST[id]";
			$db->query($sql);
		}
	}
	msg("module.php?m=payment&s=bank_account.php");
}
//===================
$sql = "select a.*,b.name,b.email from ".ACCOUNTS." a left join ".PUSER." b on a.pay_uid=b.pay_uid where a.id='$_GET[id]'";
$db->query($sql);
while($db->next_record())
{
	$id=$db->f('id');
	$bank=$db->f('bank');
	$bank_addr=$db->f('bank_addr');
	$accounts=$db->f('accounts');
	$master=$db->f('master');
	$add_time=$db->f('add_time');
	$userid=$db->f('userid');
	$active=$db->f('active');
	$censor=$db->f('censor');
	$check_time=$db->f('check_time');
	$name=$db->f('name');
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
	<div class="bigboxhead"><?php echo lang_show('bankm_a');?></div>
	<div class="bigboxbody">
        <form name="form1" method="post" action="">
          <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr>
			  <input name="id" type="hidden" id="id" value="<?php echo $id;?>">
			  <input name="userid" type="hidden" id="userid" value="<?php echo $userid;?>">
              <td width="99" align="left">审请人</td>
              <td><?php echo $name; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('bankm_c');?></td>
              <td><?php echo $bank; ?></td>
            </tr>
            <tr>
              <td align="left">开户行</td>
              <td><?php echo $bank_addr; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('bankm_d');?></td>
              <td><?php echo $master; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('bankm_e');?></td>
              <td><?php echo $accounts; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('bankm_f');?></td>
              <td>
			  <?php
				if($active==0){
					?>
			  <input type="radio" class="radio" name="result" value="0" id="r0" <?php
			  if($active==0) echo "checked"; ?>><label for="r0"><?php echo lang_show('bankm_g');?></label>
			  <input type="radio" class="radio" name="result" value="1" id="r1" <?php
			  if($active==1) echo "checked"; ?>><label for="r1"><?php echo lang_show('bankm_h');?></label>
			  <?php
				} else {
						?>
			  <?php if($active==1) echo lang_show('bankm_h'); ?>
			<?php
						}
			?>			  </td>
            </tr>
			  <?php
				if($active==0) {
					?>
            <tr > 
              <td height="20" align="center">&nbsp;</td>
              <td height="20" align="left">
                <input name="cc1" class="btn" type="submit" id="cc1" value=" <?php echo lang_show('modify');?> ">
                <input name="action" type="hidden" id="action" value="submit"></td>
            </tr>
			  <?php
				} else {
						?>
            <tr>
              <td align="left"><?php echo lang_show('bankm_j');?></td>
              <td><?php echo $censor; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('bankm_k');?></td>
              <td><?php echo dateformat($check_time); ?></td>
            </tr>
			<?php
						}
			?>
          </table>
      </form>
</div>
</div>

</body>
</html>