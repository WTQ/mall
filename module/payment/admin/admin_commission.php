<?php
include_once("../includes/page_utf_class.php");
include_once("../module/payment/lang/$config[language].php");
//====================================
if(!empty($_GET['stime']))
{
	$stime=strtotime($_GET['stime']);
	$sql.=" and a.time>'$stime' ";
}
if(!empty($_GET['etime']))
{
	$etime=strtotime($_GET['etime']);
	$sql.=" and a.time<'$etime' ";
}
	
$sqlg="select a.*,b.name,b.email from ".CASHFLOW." a left join ".PUSER." b on a.pay_uid=b.pay_uid 
where b.email='admin@systerm.com' $sql order by a.time desc";
//-----------------------------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows'))
{
	$db->query($sqlg);
	$page->totalRows = $db->num_rows();
}
$sqlg .= "  limit ".$page->firstRow.",20";
$pages = $page->prompt();
$db->query($sqlg);
$rg=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
<title><?php echo lang_show('admin_system');?></title>
</head>
<body>
<script>
function check_value()
{
	var v=document.getElementById("points").value*1;
	if(!v||v=='NaN')
		document.getElementById("charge").disabled=true;
	else
		document.getElementById("charge").disabled=false;
}
</script>
<div class="bigbox">
  <div class="bigboxhead">佣金提取明细</div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="menu">
      <tr>
        <td colspan="5"  align="left">
			<form action="" method="get">
            时间
            <input class="ltext" value="<?php echo $_GET['stime'];?>" name="stime" type="text" id="stime">
            --
            <input class="rtext" value="<?php echo $_GET['etime'];?>" name="etime" type="text" id="etime">
            <input name="Submit" type="submit" class="btn" value="搜索">
            <input name="m" type="hidden" value="payment" />
            <input name="s" type="hidden" value="admin_commission.php" />
          </form>		  </td>
      </tr>
      <tr class="theader">
        <td width="154" align="left">流水号</td>
        <td width="129"  align="left">所得拥金</td>
        <td align="left" >备注(交易订单号)</td>
        <td width="169" align="left">卖家</td>
        <td align="center">订单成交时间</td>
      </tr>
      <?php
	foreach($rg as $v)
	{
	 ?>
      <tr>
        <td align="left"><?php echo $v['flow_id'];?></td>
        <td align="10%"><?php echo $config['money'].$v['price'];?></td>
        <td align="10%"><?php echo $v['note'];?></td>
        <td width="169" align="left"><?php echo $v['censor'];?></td>
        <td align="center"><?php echo date("Y-m-d H:m:s",$v['time']);?><a onClick="return confirm('确认删除？');" href="module.php?m=payment&s=member_charge.php&del=									<?php echo $v['id'];?>"></a></td>
      </tr>
      <?php
	}
	?>
      <tr>
        <td colspan="5" align="right" ><div class="page"><?php echo $pages?></div></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
