<?php
//========================
$sql="select sum(cash) as num from ".PUSER;
$db->query($sql);
$all_sum=$db->fetchField('num');

$sql="select sum(unreachable) as num from ".PUSER;
$db->query($sql);
$unreachable=$db->fetchField('num');

$sql="select cash from ".PUSER." where email='admin@systerm.com'";
$db->query($sql);
$sys_cash=$db->fetchField('cash');
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
  <div class="bigboxhead">统计</div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="4" cellspacing="0">
      <tr>
        <td width="122" align="left">账户总额</td>
        <td width="116"><?php echo $all_sum*1.00;?> 元</td>
        <td width="761"><span class="bz">所有会员账户总额之和</span></td>
      </tr>
      <tr>
        <td align="left">冻结资金</td>
        <td><?php echo $unreachable*1.00;?> 元</td>
        <td><span class="bz">用户审请提现，但未成功，处理中的金额</span></td>
      </tr>
      <tr>
        <td align="left">交易中的金额</td>
        <td>0.00 元</td>
        <td><span class="bz">用户间交易，用户已付款，但没有确认收货,或者正在办理退货,此时由网站方托管的资金额</span></td>
      </tr>
      <tr>
        <td align="left">网站销售额</td>
        <td><?php echo $sys_cash*1.00;?> 元</td>
        <td><span class="bz">用户在网站上销费产生的费用，如交易拥金计提，广告购买等。</span></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
