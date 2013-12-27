<?php
include_once("../includes/page_utf_class.php");
include_once("../module/payment/lang/$config[language].php");
//=============================================
//----------------------删除流水
if(!empty($_GET['del']))
{
	$db->query("select * from ".CASHFLOW." where id='$_GET[del]'");
	$cre=$db->fetchRow();
	if($cre['statu']<=1)
	{//如果是取消的，或者待付款的可以直接删除
		$db->query("delete from ".CASHFLOW." where flow_id='$cre[flow_id]'");
	}
	else
	{
		$db->query("update ".CASHFLOW." set display=0 where flow_id='$cre[flow_id]'");
	}
}
//=============================
if(!empty($_GET['email']))
	$sql=" and b.email='$_GET[email]'";
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
if(!empty($_GET['admin']))
	$sql.=" and a.censor='$_GET[admin]' ";
	
$sqlg="select a.*,b.name,b.email from ".CASHFLOW." a left join ".PUSER." b on a.pay_uid=b.pay_uid 
where display=0 $sql order by a.time desc";
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
<div class="bigbox" <?php if($_GET['act']=='') echo 'style="display:block;"';else echo 'style="display:none;"';?>>
  <div class="bigboxhead tab" style=" width:90%">
  <span class="cbox"><a href="module.php?m=payment&s=member_charge.php"><?php echo lang_show('clist');?></a></span>
  <span class="cbox"><a href="module.php?m=payment&s=member_charge.php&act=add"><?php echo lang_show('mc');?></a></span>
  <span class="cbox on"><a href="#">回收站</a></span>
  </div>
  <div class="bigboxbody" style="margin-top:-1px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="menu">
      <tr height="25">
        <td height="107" colspan="7"  align="left"><form action="" method="get">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="border:none" class="searh_left">管理员名</td>
                <td style="border:none"><input class="text" value="<?php echo $_GET['admin'];?>" name="admin" type="text" id="admin">
                  <span class="bz">写入操作的管理员</span></td>
              </tr>
              <tr>
                <td>Email</td>
                <td><input class="text" value="<?php echo $_GET['email'];?>" name="email" type="text" id="email"></td>
              </tr>
              <tr>
                <td>时间</td>
                <td><input class="ltext" value="<?php echo $_GET['stime'];?>" name="stime" type="text" id="stime">
                  --
                  <input class="rtext" value="<?php echo $_GET['etime'];?>" name="etime" type="text" id="etime">
                </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input name="Submit" type="submit" class="btn" value="搜索"></td>
              </tr>
            </table>
            <input name="m" type="hidden" value="payment" />
            <input name="s" type="hidden" value="member_charge.php" />
          </form></td>
      </tr>
      <tr class="theader">
        <td width="154" align="left">流水号</td>
        <td width="100" align="left">金额</td>
        <td align="left">备注</td>
        <td width="148" align="left">操作对象</td>
        <td width="50" align="left">操作员</td>
        <td width="172" align="left">时间</td>
		<td width="60" align="left">状态</td>
        <td width="48"  align="left">操作</td>
      </tr>
    <?php
	$statu=array('取消','待处理','已付款','发货中','成功','退货中','退货成功');
	foreach($rg as $v)
	{
	 ?>
      <tr>
        <td align="left"><?php echo $v['flow_id'];?></td>
        <td align="10%"><?php echo $config['money'].$v['price'];?></td>
        <td align="10%"><?php echo $v['note'];?></td>
        <td align="left"><?php echo $v['name'].'.'.$v['email'];?></td>
        <td align="left"><?php echo $v['censor'];?></td>
        <td align="left"><?php echo date("Y-m-d H:m:s",$v['time']);?></td>
		<td align="left"><?php echo $statu[$v['statu']];?></td>
        <td align="left"><a onClick="return confirm('确认删除？');" href="module.php?m=payment&s=member_charge.php&del=<?php echo $v['id'];?>&flowid=<?php echo $v['flow_id'];?>"><?php echo $delimg;?></a></td>
      </tr>
      <?php
		}
		?>
      <tr>
        <td colspan="7" align="right" ><div class="page"><?php echo $pages?></div></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
