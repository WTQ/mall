<?php
if(!empty($_GET['tuikuan'])&&!empty($_GET['oid']))
{
	include_once($config['webroot']."/module/product/includes/plugin_order_class.php");
	$order=new order();
	$order->set_order_statu($_GET['oid'],6);
}

$sqld="select * from ".ORDER." where order_id=".$_GET['oid'];
$db->query($sqld);
$od=$db->fetchRow();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('odetail');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"> <?php echo lang_show('odetail');?></div>
  <div class="bigboxbody">
    <table width="100%"  border="0" cellspacing="1" cellpadding="6" align="center">
      <tr class="theader">
        <td height="31" colspan="2" >订单详情</td>
      </tr>
      <tr>
        <td height="191" colspan="2" ><table width="100%" border="0"cellspacing="1" cellpadding="6">
          <tr>
            <td width="13%" height="31"><?php echo lang_show('oid');?></td>
            <td width="87%"><?php echo $od['order_id'];?></td>
          </tr>
          <tr>
            <td height="21" >定单状态</td>
            <td ><?php
				include("../lang/$config[language]/company_type_config.php");
				$sta=$od['status'];
				echo $order_status[$sta];
				if($sta==5)
				{
					echo "　<a href='module.php?m=product&s=order_detail.php&tuikuan=true&oid=$od[order_id]'>确认退货</a>";
				}
				?></td>
          </tr>
          <tr>
            <td height="21" ><?php echo lang_show('otime');?></td>
            <td ><?php echo date("Y-m-d H:m",$od['creat_time']);?></td>
          </tr>
          <tr>
            <td height="21" ><?php echo lang_show('buyername');?></td>
            <td ><?php echo $od['buyer_name'];?></td>
          </tr>
          <tr>
            <td height="21" ><?php echo lang_show('buyeraddr');?></td>
            <td ><?php echo $od['buyer_addr'];?></td>
          </tr>
          <tr>
            <td height="25" ><?php echo lang_show('buyertel');?></td>
            <td ><?php echo $od['buyer_tel'];?></td>
          </tr>
          <tr>
            <td height="25" ><?php echo lang_show('buyerzip');?></td>
            <td ><?php echo $od['buyer_zip'];?></td>
          </tr>
          <tr>
            <td height="25" >运送方式</td>
            <td ><?php echo $od['logistics_type'];?></td>
          </tr>
          <tr>
            <td height="25" >运送总价</td>
            <td ><?php echo number_format($od['logistics_price'],2);?>元</td>
          </tr>
          <tr>
            <td height="25" >商品总价</td>
            <td ><?php echo  number_format($od['product_price'],2);?>元(不含运费)</td>
          </tr>
          <tr>
            <td height="30" colspan="2" ><?php echo $od['des'];?></td>
          </tr>
        </table>          <p>&nbsp;</p></td>
      </tr>
      <tr class="theader">
        <td colspan="2" ><?php echo lang_show('pdetail');?></td>
      </tr>
      <?php
		   $sql="select * from ".ORPRO." where order_id=$od[order_id]";	
		   $db->query($sql);
		   $re=$db->getRows();
		   foreach($re as $key=>$pro)
		   {
		 ?>
      <tr>
        <td width="114" height="31" > 
           <a href='../?m=product&s=detail&id=<?php echo $pro['pid'];?>' target='_blank'>
              
			  <?php if(!empty($pro['pic'])){ ?>
             <img src="<?php echo $pro['pic'];?>"  width="80" height="80"/>
			  <?php }else{ ?> 
             <img src="../image/default/nopic.gif"  width="80" height="80"/>
			 <?php }?> 
             
           </a>		</td>
        <td width="1165" align="left" valign="top" >
		 <?php
			 echo "产品名称：<a href='../?m=product&s=detail&id=$pro[pid]' target='_blank'>".$pro['name']."</a><br>";
			 echo "产品单价：". number_format($pro['price'],2)."元<br>";
			 echo "产品数量：".$pro['num'];
		  ?></td>
      </tr>
      <?php
		}
       ?>
    </table>
  </div>
</div>
</body>
</html>