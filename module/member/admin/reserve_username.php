<?php
include_once("../includes/page_utf_class.php");
//====================================
if(!empty($_GET['action'])&&$_GET['action']=="delone"&&!empty($_GET['delid']))
{
	$sql="delete from ".RESERVE_USERNAME." where id=".$_GET['delid']; 
	$db->query($sql);
	msg("?m=member&s=reserve_username.php"); 
}
if(!empty($_POST['submit'])&&!empty($_POST['delete']))
{
	if(is_array($_POST['delete']))
	{
		$delid=implode(",",$_POST['delete']);
		$sql="delete from ".RESERVE_USERNAME." where id in ($delid)";
		$db->query($sql);
	}
}
if(!empty($_POST['addreservename'])&&$_POST['addreserve']==lang_show('add_name'))
{
	$reservename=$_POST['addreservename'];
	$sql="insert into ".RESERVE_USERNAME." (`username`) values ('$reservename')"; 
	$db->query($sql);
	msg("?m=member&s=reserve_username.php"); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('m_mang');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">
function checkall()
{
	 for(var j = 0 ; j < document.getElementsByName("delete[]").length; j++)
	 {
	  	if(document.getElementsByName("delete[]")[j].checked==true)
	  	  document.getElementsByName("delete[]")[j].checked = false;
		else
		  document.getElementsByName("delete[]")[j].checked = true;
	 }
}
</script>
<div class="guidebox"><?php echo lang_show('m_mang');?>&raquo; <?php echo lang_show('res_name');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('m_mang');?></div>
	<div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="4" align="left" >
		  <form name="reservename" action="" method="post">
            <input name="addreservename" type="text" id="addreservename" class="text">
            <input class="btn" type="submit" name="addreserve" id="addreserve" value="<?php echo lang_show('add_name');?>" ><?php echo lang_show('resmsg');?>
		  </form>
		  </td>
        </tr>
		<form name="reservename" action="" method="post">
        <tr  class="theader">
          <td align="left"  width="3%">
            <input type="checkbox" class="checkbox" name="Input"  onClick="checkall()">
            </td>
            <td>
            <?php echo lang_show('res_name');?></td>
          <td align="left" ><?php echo lang_show('res_stuta');?></td>
          <td align="left" ><?php echo lang_show('res_op');?></td>
        </tr>
        <?php
           unset($sql);
           $sql="select * from ".RESERVE_USERNAME." order by username asc";
           $db->query($sql);
           $re=$db->getRows();
           foreach($re as $v)
	       {
        ?>
        <tr>
          <td align="left"><input name="delete[]" type="checkbox" class="checkbox"  value="<?php echo $v['id'];?>"/></td>
          <td><?php echo $v['username']; ?></td>
          <td width="228" align="left" ><?php echo lang_show('resing');?></td>
          <td width="242"  align="left" ><a href="?m=member&s=reserve_username.php&action=delone&delid=<?php echo $v['id'];?>" class="STYLE15" onClick="javascript:return confirm('<?php echo lang_show('delask');?>')"><?php echo $delimg;?></a></td>
        </tr>
        <?php
        }
        ?>
         <tr>
          <td  colspan="4" align="left"  ><input class="btn" type="submit" name="submit" value="<?php echo lang_show('delall');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"></td>
        </tr>
		</form>
      </table>
	</div>
</div>
</body>
</html>