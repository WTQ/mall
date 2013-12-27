<?php
//========================
if(isset($_POST["action"])&&!empty($_POST['id'])&&isset($_POST["result"]))
{
	$add_time = time();
	if($_POST["result"]==1)//成功
	{	
		//-------------------更新提现记录，增加备注一栏，记录处理的流水号等。
		$sql = "update ".CASHPICKUP." set 
			is_succeed=1,bankflow='$_POST[bankflow]',con='$_POST[con]',
			check_time='$add_time', censor='$_SESSION[ADMIN_USER]' 
			where id='$_POST[id]'";
		$db->query($sql);
		
		//------------------更新不可用资金
		$sql = "update ".PUSER." set unreachable=unreachable-".$_POST['amount']." where pay_uid=$_POST[userid]";
		$db->query($sql);
		//------------------更新流水状态
		$sql="update ".CASHFLOW." set statu=4 where order_id='$_POST[id]' and pay_uid='$_POST[userid]'";
		$db->query($sql);
	}
	else if($_POST["result"]==2)//失败
	{	
		//----------------------更新提现记录
		$sql = "update ".CASHPICKUP." set 
		is_succeed=2, check_time='$add_time', censor='$_SESSION[ADMIN_USER]',bankflow='$_POST[bankflow]',con='$_POST[con]'
		where id=$_POST[id]";
		$db->query($sql);
		//----------------------处理不可以用资金
		$sql = "update ".PUSER." set unreachable=unreachable-".$_POST['amount']." where pay_uid=$_POST[userid]";
		$db->query($sql);
		
		//----------------------增加可用资金
		$sql = "update ".PUSER." set cash=cash+".$_POST['amount']." where pay_uid=$_POST[userid]";
		$db->query($sql);
		//----------------------更新流水状态为0
		$sql="update ".CASHFLOW." set statu=0 where order_id='$_POST[id]' and pay_uid='$_POST[userid]'";
		$db->query($sql);
	}
	msg("module.php?m=payment&s=pickuplist.php");
}
//===================
$sql = "select a.*,b.* from ".ACCOUNTS." a, ".CASHPICKUP." b where a.id=b.bank_id and b.id='$_GET[id]'";
$db->query($sql);
while($db->next_record())
{
	$id=$db->f('id');
	$bank=$db->f('bank');
	$accounts=$db->f('accounts');
	$master=$db->f('master');
	$amount=$db->f('amount');
	$add_time=$db->f('add_time');
	$userid=$db->f('userid');
	$is_succeed=$db->f('is_succeed');
	$censor=$db->f('censor');
	$check_time=$db->f('check_time');
	$bankflow=$db->f('bankflow');
	$con=$db->f('con');
	$cashflowid=$db->f('cashflowid');
	$pay_uid=$db->f('pay_uid');
}
$db->query("select name,email from ".PUSER." where pay_uid='$pay_uid'");
$pud = $db->fetchRow();
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
	<div class="bigboxhead"><?php echo lang_show('pickupm_a');?></div>
	<div class="bigboxbody">
        <form name="form1" method="post" action="">
          <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr>
			  <input name="id" type="hidden" id="id" value="<?php echo $id;?>">
			  <input name="amount" type="hidden" id="amount" value="<?php echo $amount;?>">
			  <input name="userid" type="hidden" id="userid" value="<?php echo $pay_uid;?>">
              <td width="131" align="left">审请人</td>
              <td width="768"><?php echo $pud['name'].$pud['email']; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_c');?></td>
              <td><?php echo $bank; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_d');?></td>
              <td><?php echo $master; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_e');?></td>
              <td><?php echo $accounts; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_f');?></td>
              <td><?php echo $amount; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_g');?></td>
              <td>
			  <?php
				if($is_succeed==0)
				{
			  ?>
			  <input type="radio" class="radio" name="result" value="1" id="r1" <?php
			  if($is_succeed==1) echo "checked"; ?>><label for="r1"><?php echo lang_show('pickupm_i');?></label>
			  <input type="radio" class="radio" name="result" value="2" id="r2" <?php
			  if($is_succeed==2) echo "checked"; ?>><label for="r2"><?php echo lang_show('pickupm_j');?></label>
			  <?php
				}
				else
				{
			  ?>
			  <?php if($is_succeed==1) echo lang_show('pickupm_i'); else if($is_succeed==2) echo lang_show('pickupm_j'); ?>
			 <?php
				}
			 ?>
			 </td>
            </tr>
			  <?php if($is_succeed==0){ ?>
              <tr >
                <td height="20" align="left">回执流水号</td>
                <td height="20" align="left">
                  <input class="text" type="text" name="bankflow" id="bankflow">
                </td>
              </tr>
              <tr >
                <td height="20" align="left">备注</td>
                <td height="20" align="left">
                  <textarea class="text" name="con" id="con" cols="45" rows="5"></textarea>
                </td>
              </tr>
            <tr > 
              <td height="20" align="left">&nbsp;</td>
              <td height="20" align="left">
                <input name="cc1" class="btn" type="submit" id="cc1" value=" <?php echo lang_show('modify');?> ">
                <input name="action" type="hidden" id="action" value="submit">
                <input type="hidden" name="cashflowid" value="<?php if(!empty($cashflowid)) echo $cashflowid;?>">
              </td>
            </tr>
			  <?php
				} else {
			  ?>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_k');?></td>
              <td><?php echo $censor; ?></td>
            </tr>
            <tr>
              <td align="left"><?php echo lang_show('pickupm_l');?></td>
              <td><?php echo dateformat($check_time); ?></td>
            </tr>
              <tr>
                <td align="left">回执流水号</td>
                <td><?php echo $bankflow; ?>&nbsp;</td>
              </tr>
              <tr>
                <td align="left">备注</td>
                <td><?php echo $con; ?>&nbsp;</td>
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