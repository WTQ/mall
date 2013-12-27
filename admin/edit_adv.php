<?php
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=============================================
$submit=isset($_POST['submit'])?$_POST['submit']:NULL;
if('dele'==$_GET['action']&&isset($_GET["id"]))
{
	$db->query("delete from ".ADVS." where ID='$_GET[id]'");
	$db->query("delete from ".ADVSCON." where group_id='$_GET[id]'");
	msg('advs.php');
}
if($submit==lang_show('submit')&&isset($_GET["id"]))
{	
	$date=date("Y-m-d H:i:s");
	$sql="UPDATE ".ADVS." SET con='$_POST[con]',name='$_POST[name]',ad_type='$_POST[ad_type]',width='$_POST[width]',height='$_POST[height]',date='$date',total='$_POST[total]',price='$_POST[price]',unit='$_POST[unit]',`group`='$_POST[group]' WHERE ID=$_GET[id]";
	$re=$db->query($sql);
	if($re==true)
		msg("advs.php");
}
elseif($submit&&!isset($_GET["id"]))
{
	$date=date("Y-m-d H:i:s");
	$sql="insert into ".ADVS." (con,ad_type,name,date,width,height,price,unit,`group`)
	      values
	      ('$_POST[con]','$_POST[ad_type]','$_POST[name]','$date','$_POST[width]','$_POST[height]','$_POST[price]','$_POST[unit]','$_POST[group]')";
	if($db->query($sql)==true)
		msg("advs.php");
}
//============================================
if(isset($_GET["id"]))
{
	$sql="SELECT * FROM ".ADVS." WHERE ID=$_GET[id]";
	$db->query($sql);
	$de=$db->fetchRow();
}
else
	$de=NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script>
	function check_value()
	{
		if(document.form1.name.value=='')
		{
			alert("请输入名称！");
			document.form1.name.focus();
			return false;		
		}
		var reNum=/^[0-9]+.?[0-9]*$/;
		if(!reNum.test(document.getElementById('price').value))
		{
			alert("请输入数字！");
			document.getElementById('price').value="0";
			document.getElementById('price').focus();
			return false;
		}
		if(!reNum.test(document.form1.total.value))
		{
			alert("请输入数字！");
			document.form1.total.value="0";
			document.form1.total.focus();
			return false;
		}
	}
</script>
</HEAD>
<body>
<form action="" method="post" enctype="multipart/form-data" name="form1" onsubmit='return check_value();'>
<div class="bigbox">
	<div class="bigboxhead">
	<span style="float:left"><?php  if(isset($_GET["id"]))echo lang_show('medit');else echo lang_show('advsadd');?><?php echo lang_show('advplace');?></span>
	</div>
	<div class="bigboxbody"><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <?php
  if(isset($_GET["id"]))
  {
  ?>
  <tr>
    <td ><?php echo lang_show('advplnum');?></td>
    <td ><?php echo $_GET["id"];?></td>
  </tr>
  <?php } ?>
    <tr>
    <td ><?php echo lang_show('advplname');?></td>
    <td ><input name="name" type="text" class="text" value="<?php echo $de["name"];?>"/></td>
    </tr>
	  <tr>
    <td>广告位分组</td>
    <td><input name="group" type="text" class="text" value="<?php echo $de["group"];?>"/></td>
    </tr>
  <tr>
    <td class="body_left"><?php echo lang_show('adv_l_type');?> </td>
    <td width="85%">
      <select class="select" name="ad_type" id="ad_type">
        <option value="1" <?php if(!empty($de['ad_type'])&&$de['ad_type']==1) echo "selected";?>><?php echo lang_show('gen_adv');?></option>
        <option value="2" <?php if(!empty($de['ad_type'])&&$de['ad_type']==2) echo "selected";?>><?php echo lang_show('flash_adv');?></option>
        <option value="3" <?php if(!empty($de['ad_type'])&&$de['ad_type']==3) echo "selected";?>><?php echo lang_show('duilian_adv');?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td><?php echo lang_show('wh');?></td>
    <td>
      <input class="ltext" name="width" type="text" id="width"  value="<?php if(!empty($de["width"]))echo $de["width"];?>"> -- <input class="rtext" name="height" type="text" id="height" value="<?php if(!empty($de["height"]))echo $de["height"];?>">
   </td>
  </tr>
    <td ><?php echo lang_show('price');?></td>
    <td ><input type="text" class="ltext" name="price" id='price' style='text-align:right;' maxlength='8' value="<?php echo $de["price"]*1;?>"/><?php echo $config['money'];?>/
			<select class="lselect" name='unit'>
				<option value='day'><?php echo $lang['day'];?></option>
				<option value='week'><?php echo $lang['week'];?></option>
				<option value='month'><?php echo $lang['month'];?></option>
			</select>
	</td>
  </tr>
 <tr>
    <td ><?php echo lang_show('adv_total');?></td>
    <td ><input type="text" class="text" name="total"  style='text-align:right;' maxlength='3' value="<?php echo $de["total"]*1;?>"/><span class="bz">(设为0时不可购买)</span>
	</td>
  </tr>
  <tr>
    <td><?php echo lang_show('advpldes');?></td>
    <td><textarea name="con" class="text" cols="80" rows="10" id="con"><?php echo $de["con"];?></textarea></td>
    </tr>
<?php if(isset($_GET["id"])){?>
  <tr>
    <td><?php echo lang_show('use_code');?></td>
    <td>
      <input class="text" name="js" type="text" id="js" value="<script src='&lt;{$config.weburl}&gt;/api/ad.php?id=<?php echo $de["ID"]; ?>&catid=&lt;{$smarty.get.id}&gt;&name=&lt;{$smarty.get.key}&gt'></script>" size="70">
    </td>
  </tr>
<?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td>
    <input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>" />
    <input class="btn" type="reset"  value="<?php echo lang_show('reset');?>" />
    </td>
  </tr>
</table>
  </div>
</div>
</form>
</body>
</html>