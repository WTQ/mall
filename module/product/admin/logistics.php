<?php
	session_start();
	include_once("../includes/global.php");
	//=====================================
	
	if(!empty($_POST['company'])&&!empty($_POST['number'])&&!empty($_POST['time']))
	{
		$_POST['time']=strtotime($_POST['time']);
		$sql="insert into ".DELIVERY."(company,number,time,order_id,user,uptime) values('$_POST[company]','$_POST[number]','$_POST[time]',$_POST[order_id],'$_SESSION[ADMIN_USER]','".time()."')";
		$db->query($sql);
		$id=$db->lastid();
		$sql="update ".ORDER." set status='3',uptime=".time().",logistics='$id' where order_id='$_POST[order_id]'";
		$db->query($sql);
		
	    unset($_GET['id']); 
		unset($_GET['m']); 
		unset($_GET['s']);
		msg("module.php?m=product&s=shop_order.php&".implode('&',convert($_GET)));
	}
	$sql="select * from ".FASTMAIL."  order by id";
	$db->query($sql);
	$re=$db->getRows();

?>
<style>
table{ line-height: 26px; border-right:1px solid #E6E6E6; }
table td{ border-color: #E6E6E6; border-image: none; border-style: solid; border-width: 1px 0 0 1px; padding: 3px 4px;}
</style>
<form action="" method="post" target="_parent" >
<input type="hidden" name="de" id="de" value="" />
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td width="80">快递公司：</td>
        <td>
        	<?php if(!$re) { ?>
        	<input type="text" name="company"  style="width:260px" />
            <?php } else {?>
        	<select name="company" id="company">
            <?php
			 foreach ($re as $v)
          	 {
				 echo "<option value='$v[company]'>$v[company]</option>";
			 }
			?>
            </select>
            <?php } ?>
        </td>
	</tr>
	<tr>
		<td>快递单号：</td>
        <td><input type="text" name="number" style="width:260px" /></td>
	</tr>
    <tr>
		<td>发货时间：</td>
        <td>
        <input type="text" name="time"  id="time" style="width:160px" />
        <input class="btn" value="设为当前时间" type="button" onClick="document.getElementById('time').value='<?php echo date("Y-m-d H:i:s") ?>'">
        </td>
	</tr>
	<tr>
		<td colspan="2">
        	<input type="hidden" name="order_id" value="<?php echo $_GET['id']; ?>" />
			<input type="submit" name="submit" id="submit" value="<?php echo lang_show('subsend');?>" />&nbsp;
			<input type="reset" value="<?php echo lang_show('subreset');?>" />
		</td>
	</tr>
</table>
</form>

