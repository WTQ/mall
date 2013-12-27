<?php
/**
 *Coupright B2Bbuilder
 *Powered by http://www.b2b-builder.com
 *Auther:Brad zhang
 *Des: company cat
 *Date:2008-11-14
 */
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=====================================
if(isset($_GET["deid"]))
{
	$sql="delete from ".DOMAIN." WHERE id='$_GET[deid]'";
	$db->query($sql);
}
if(isset($_GET["isopen"]))
{
	$sql="update ".DOMAIN." set isopen=$_GET[isopen] WHERE id='$_GET[id]'";
	$db->query($sql);
}
//=======================================
$sql="select * from ".DOMAIN." where 1 ";
$db->query($sql);
$re=$db->getRows();
//=========================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>

<BODY>
<div class="bigbox">
	<div class="bigboxhead tab" style=" width:90%">
    <span class="cbox on"><a href="#">管理分站</a></span>
    <span class="cbox"><a href="add_sub_domain.php"><?php echo lang_show('add_type');?></a></span>
    </div>
	
	<div class="bigboxbody" style="margin-top:-1px;">
	<form method="post" action="">
<table width="100%" align="center" cellpadding="0" cellspacing="0">
  <tr class="theader">
    <td width="27%" ><?php echo lang_show('domain');?></td>
    <td width="56%" ><?php echo lang_show('con');?></td>
    <td align="center"><?php echo lang_show('option');?></td>
    </tr>
  <?php
  foreach($re as $v)
  {
  ?>
  <tr >
    <td class="hh">http://<?php echo $v["domain"].'.'.$config['baseurl']; ?></td>
    <td class="hh"><?php echo $v["con"].$v["con2"].$v["con3"]; ?><br><?php echo strip_tags($v["des"])?></td>
    <td align="center" >
    <a href="add_sub_domain.php?edit=<?php echo $v["id"]; ?>"><?php echo $editimg;?></a>
	<a href="?deid=<?php echo $v["id"]; ?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"><?php echo $delimg;?></a>
	<?php if($v["isopen"]==1){?>
	<a href="?isopen=0&id=<?php echo $v["id"]; ?>"><?php echo $startimg;?></a>
	<?php }else{?>
	<a href="?isopen=1&id=<?php echo $v["id"]; ?>"><?php echo $stopimg;?></a>
	<?php } ?>	</td>
    </tr>
  <?php
  }
  ?>
</table>
	</form>
</div>
</div>

</BODY>
</HTML>
