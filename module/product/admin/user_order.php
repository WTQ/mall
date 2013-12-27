<?php
include_once("../includes/page_utf_class.php");
//=============================================
if(!empty($_GET['deid']))
{	
	$sql="update ".ORDER." set status='-1' where order_id='$_GET[deid]'";
	$db->query($sql);
}

if(!empty($_GET['username']))
{
    $sqlc="select userid from ".ALLUSER." where user='".$_GET['username']."'";
	$db->query($sqlc);
	$us=$db->fetchRow();
    if(!empty($_GET['ordertype'])&&$_GET['ordertype']=='b')
       $subsql.=" and buyer_id='".$us['userid']."'";
	elseif (!empty($_GET['ordertype'])&&$_GET['ordertype']=='s')
	   $subsql.=" and seller_id='".$us['userid']."'";
	else
       $subsql.=" and ( buyer_id='".$us['userid']."' or seller_id='".$us['userid']."')";
}

if($_GET['orderstatus']!='')
	$subsql.=" and status='".$_GET['orderstatus']."'";
$sql="select * from ".ORDER." where buyer_id='' and userid!=''  $subsql  order by id desc";
//=============================
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('orderm');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('oum');?></div>
  <div class="bigboxbody">
    <form method="get" action="">
      <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="searh_left"><?php echo lang_show('ouname');?></td>
          <td><input class="text" type="text" name="username" id="username" value="<?php echo $_GET['username'];?>" />
          </td>
        </tr>
        <tr>
          <td ><?php echo lang_show('ostatu');?></td>
          <td ><select class="select" name="orderstatus" id="orderstatus">
              <option value=""><?php echo lang_show('otypeall');?></option>
              <?php
			 include("../lang/$config[language]/company_type_config.php");
			 foreach($order_status as $key=>$v)
			 {
			 ?>
              <option value="<?php echo $key;?>" <?php if($_GET['orderstatus']==$key&&$_GET['orderstatus']!='') echo "selected";?>> <?php echo $v;?> </option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>" />
            <input name="m" type="hidden" value="product" />
            <input name="s" type="hidden" value="user_order.php" /></td>
        </tr>
      </table>
    </form>
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr class="theader">
        <td width="13%" align="left"><?php echo lang_show('ordid');?></td>
        <td width="25%"><?php echo lang_show('ostatu');?></td>
        <td width="11%"><?php echo lang_show('price');?></td>
        <td width="27%"><?php echo lang_show('obuyer');?></td>
        <td width="12%" align="left" ><?php echo lang_show('otime');?></td>
        <td width="12%" align="center" ><?php echo lang_show('option');?></td>
      </tr>
      <?php
	  //----送货方式
	  foreach ($re as $v)
	  {
	 ?>
      <tr>
        <td width="13%" align="left" ><a href="?m=product&s=order_detail.php&oid=<?php echo $v['order_id'];?>"><?php echo $v['order_id']; ?></a></td>
        <td width="25%" ><?php echo $order_status[$v['status']]?>&nbsp;</td>
        <td width="11%" ><?php echo number_format($v['product_price']+$v['logistics_price'],2)?></td>
        <td width="27%"><?php echo $v['buyer_name']?>/<?php echo $v['buyer_tel']?><?php echo $v['buyer_mobile']?></td>
        <td width="12%" align="left"><?php echo date("Y-m-d H:i",$v['creat_time']); ?></td>
        <td width="12%" align="center"><a href="sendmail.php?userid=<?php echo $v['seller_id']; ?>"><?php echo $mailimg;?></a> <a href="?m=product&s=order_detail.php&oid=<?php echo $v['order_id'];?>"><?php echo $editimg; ?></a>
          <?php if($v['status']==0||$v['status']==4||$v['status']==6){?>
          <a onClick="return confirm('确信删除吗？');" href="?m=product&s=user_order.php&deid=<?php echo $v['order_id'];?>"><?php echo $delimg;?></a>
          <?php } ?>
        </td>
      </tr>
      <?php 
        }
	?>
      <tr>
        <td colspan="7" align="right"><div class="page"><?php echo $pages?></div></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>