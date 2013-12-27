<?php
include_once("../includes/page_utf_class.php");
//=================================================
if(!empty($_POST["del"])&&count($_POST["del"])>0)
{
	for($i=0;$i<count($_POST["del"]);$i++)
	{
		$id=$_POST["del"][$i];
		$db->query("delete from ".FEEDBACK." where id='$id'");
	}
}
//--------------------------------------------------
if(!empty($_GET['key']))
	$psql=" and (sub like '%$_GET[key]%' or con like '$_GET[key]' )";
if(!empty($_GET['from']))
	$psql.=" and fromuserid in (select userid from ".USER." where company='$_GET[from]')";
if(!empty($_GET['to']))
	$psql.=" and touserid in (select userid from ".USER." where company='$_GET[to]')";
$sql="select * from ".FEEDBACK." where 1 $psql order by id desc"; 
//-----------------
$page = new Page;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.", ".$page->listRows;
$pages = $page->prompt();
//----------------
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("del[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("del[]")[j].checked==true)
	  	  document.getElementsByName("del[]")[j].checked = false;
		else
		  document.getElementsByName("del[]")[j].checked = true;
	 }
}
</script>


<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('msg_list');?></div>
	<div class="bigboxbody">
<form method="get" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="searh_left">发送方</td>
    <td><input name="from" class="text" type="text" id="from" value="<?php if(!empty($_GET['from'])) echo $_GET['from'];?>"></td>
  </tr>
  <tr>
    <td>接受方</td>
    <td><input name="to" type="text" class="text" id="to" value="<?php if(!empty($_GET['to'])) echo $_GET['to'];?>"></td>
  </tr>
  <tr>
    <td>关键词</td>
    <td><input type="text" name="key" class="text" value="<?php if(!empty($_GET['key'])) echo $_GET['key'];?>"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>"></td>
  </tr>
</table>
</form>
<form action="" method="post">
<table width="100%" border="0" cellpadding="4" cellspacing="0" >
  <tr class="theader"> 
    <td width="20"><input type="checkbox" class="checkbox" name="checkbox" onClick="do_select()" value="checkbox" /></td>
    <td width="*"><?php echo lang_show('content');?></td>
    <td width="80"><?php echo lang_show('send');?></td>
    <td width="163"><?php echo lang_show('receive');?></td>
    <td width="120"><?php echo lang_show('time');?></td>
  </tr>
  <?php 
	for($i=0;$i<count($re);$i++)
	{
		$id=$re[$i]['id'];
		$sub=$re[$i]['sub'];
		$con=$re[$i]['con'];
		$fid=$re[$i]['fromuserid'];
		$touserid=$re[$i]['touserid'];
		$dat=$re[$i]['date'];
		if(!empty($fid))
		{
			$db->query("select company from ".USER." where userid='$fid'");
			$com=$db->fetchRow();
			$fcompany=$com['company'];
		}
		else
			$fcompany=NULL;
		if(!empty($touserid))
		{
			$db->query("select company from ".USER." where userid='$touserid'");
			$com=$db->fetchRow();
			$tcompany=$com['company'];
		}
		else
			$fcompany=NULL;
	?>
  <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')"> 
    <td valign="top"><input type="checkbox" class="checkbox" name="del[]" id="de<?php echo $i?>" value="<?php echo $id; ?>">
      <?php
	if(empty($re[$i]["iflook"]))
		echo '<img src="../image/default/unred.gif">';
	if($re[$i]["iflook"]==1)
		echo '<img src="../image/default/read.gif">';
	if($re[$i]["iflook"]==2)
		echo '<img src="../image/default/off.gif">';
	if($re[$i]["iflook"]==3)
		echo '<img src="../image/default/replied.gif">';
	?></td>
    <td><div style="overflow:auto; width:100%; height:50px;"><?php echo "$sub<br>";echo "$con"; ?></div>		</td>
    <td>
	<?php
	 if(!empty($fcompany))
	 	 echo "<a href='".$config['weburl']."/shop.php?uid=".$fid."' target='_blank' >".$fcompany.'</a>';
	 else
	 	echo $re[$i]["fromInfo"];
	 ?>&nbsp;</td>
    <td>
		<?php echo "<a href='".$config['weburl']."/shop.php?uid=".$touserid."' target='_blank' >".$tcompany."</a>"; ?>
		[<a href="sendmail.php?userid=<?php echo $touserid;?>">提醒</a>]
	</td>
    <td><?php echo $dat;?></td>
  </tr>
   <?php 
	}
	?> 
  <tr>
    <td colspan="1" align="left"><input class="btn" type="submit" name="Submit2" value="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"></td>
    <td colspan="6" align="right"><div class="page"><?php echo $pages?></div></td>
    </tr>
</table>
</form>
</div>
</div>

</body>
</html>

