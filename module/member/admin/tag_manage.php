<?php
include_once("../includes/page_utf_class.php");
//====================================
if(empty($_GET['deltag'])&&!empty($_GET['tagname']))
{
	$sql="delete from ".TAGS." where tagname='".trim($_GET['tagname'])."'";
	$db->query($sql);
	$sql="delete from ".CTAGS." where tagname='".trim($_GET['tagname'])."'";
	$db->query($sql);
}
if(!empty($_POST['delete'])&&$_POST['delete']==lang_show('delete')&&is_array($_POST["checktag"]))
{
	$deltn=implode("','",$_POST["checktag"]);
	$sql="delete from ".TAGS."  where  tagname in ('".$deltn."')";
	$db->query($sql);
	$sql="delete from ".CTAGS." where  tagname in ('".$deltn."')";
	$db->query($sql);	
}

if(!empty($_POST['name']))
	$ssql=" and tagname like '%$_POST[name]%' ";
	
$sql="select * from ".TAGS." where 1 $ssql ";

//=============================
$page = new Page;
$page->listRows=200;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",200";
$pages = $page->prompt();
//=====================
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">
function checkall()
{
	 for(var j = 0 ; j < document.getElementsByName("checktag[]").length; j++)
	 {
	  	if(document.getElementsByName("checktag[]")[j].checked==true)
	  	  document.getElementsByName("checktag[]")[j].checked = false;
		else
		  document.getElementsByName("checktag[]")[j].checked = true;
	 }
}
</script>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('taglist');?></div>
	<div class="bigboxbody">
	<form name="" action="" method="post">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="4" align="left" >
            <input class="text" name="name" type="text" id="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name'];?>" >
            <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('search');?>">
			</td>
        </tr>
        <tr class="theader">
          <td align="left"  width="3%">
          <input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()"></td><td>TAG</td>
          <td width="134" align="left"   ><?php echo lang_show('clicknum');?></td>
          <td width="307" align="left"   ><?php echo lang_show('action');?></td>
        </tr>
        <?php
	      foreach ($re as $v)
          {
        ?>
        <tr>
          <td align="left" >
          <input type="checkbox" class="checkbox" name="checktag[]" value="<?php echo $v['tagname'];?>"></td><td>		    <?php echo $v['tagname'];?></td>
          <td align="left" ><?php echo $v['total'];?></td>
          <td align="left" ><a href="?m=member&s=tag_manage.php&tagname=<?php echo urlencode($v['tagname']);?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"><?php echo $delimg;?></a></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td align="left" colspan="2">
          <input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()">
		  <input  class="btn" type="submit" name="delete" id="delete" value="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');">&nbsp;		  </td>
          <td colspan="2" align="right"><div class="page"><?php echo $pages;?></div></td>
        </tr>
	</table>
	</form>
	</div>
</div>
</body>
</html>