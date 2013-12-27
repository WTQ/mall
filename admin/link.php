<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//======================================
$step=isset($_GET["step"])?$_GET["step"]:NULL;
if(!empty($_POST['dellink']))
{
	$id=implode(",",$_POST["de"]);
	$db->query("delete from ".LINK." where linkid in($id)");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8"> 
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("de[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("de[]")[j].checked==true)
	  	  document.getElementsByName("de[]")[j].checked = false;
		else
		  document.getElementsByName("de[]")[j].checked = true;
	 }
}
</script>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('link');?></div>
	<div class="bigboxbody">
	<form action="" method="get">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		<td><?php echo lang_show('link_name');?></td>
		<td><input class="text" name="keyword" type="text" id="keyword"  /></td>
	  </tr>
		<tr>
		<td  class="searh_left"><?php echo lang_show('statu');?></td>
		<td>
		  <select class="select" name="pass" id="select">
			<option value="0"><?php echo lang_show('close');?></option>
			<option value="1" <?php if(isset($_GET["pass"])&&$_GET['pass']==1)echo "selected";?>><?php echo lang_show('open');?></option>
			<option value="2" <?php if(isset($_GET["pass"])&&$_GET['pass']==2)echo "selected";?>><?php echo lang_show('recommend');?></option>
		  </select>
		  </td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td><input name="Input" class="btn" type="submit" value=" <?php echo lang_show('search');?> " /></td>
	  </tr>
		</table>
	</form>


    <form action="" method="post">
	  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
		<tr class="theader">
		  <td width="3%" align="left" ><input onClick="do_select();" type="checkbox" class="checkbox" name="checkbox2" id="checkbox2"></td> 
		  <td width="25%" ><?php echo lang_show('link_name');?></td>
		  <td width="11%" ><?php echo lang_show('sort');?></td>
		  <td width="19%" ><?php echo lang_show('link');?></td>
		  <td width="11%" >Logo</td>
		  <td width="17%" ><?php echo lang_show('VaildPeriod');?></td>
		  <td width="8%" ><?php echo lang_show('statu');?></td>
		  <td width="6%" ><?php echo lang_show('manager');?></td>
		</tr>
		<?php
		if(isset($_GET["pass"]))
			$ksql=" and statu='$_GET[pass]'";
		if(isset($_GET["keyword"]))
			$ksql.=" and name like '%$_GET[keyword]%' ";
		
		
			
		$sql="select * from ".LINK." where 1 $ksql $tsql order by statu desc, linkid desc";
		//=============================
		include_once("../includes/page_utf_class.php");
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
		$i=0;
		while($db->next_record())
		{ $i++;
		?>
           
            <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')" >
              <td align="left"><input type="checkbox" class="checkbox" name="de[]" id="checkbox" value="<?php echo $db->f('linkid'); ?>"></td> 
              <td>
			  <?php  echo $db->f('name'); ?></td>
              <td><?php  echo $db->f('orderid'); ?></td>
              <td width="19%" ><a title="<?php echo $db->f('con');?>" href="<?php  echo $db->f('url'); ?>" target="_blank"><?php  echo $db->f('url'); ?></a> </td>
              <td width="11%" >&nbsp;<?php if($db->f('log')){?><img src="<?php  echo $db->f('log'); ?>"><?php }?></td>
              <td width="17%" >
			  <?php 
			  	if($db->f('etime')-time()<0)
					echo '<font color=red>*</font>';
				echo date("Y-m-d",$db->f('stime'));?>/<?php echo date("Y-m-d",$db->f('etime'));
			  ?>              </td>
              <td width="8%" ><?php echo $db->f('statu');?></td>
              <td width="6%" ><a href="add_link.php?step=edit&linkid=<?php echo $db->f('linkid'); ?>"><?php echo $editimg;?></a></td>
            </tr>
       <?php
		}
		?>
         <tr>
               <td align="left"> <input onClick="do_select();" type="checkbox" class="checkbox" name="checkbox2" id="checkbox2"></td>
			   <td> <input onClick="return confirm('<?php echo lang_show('are_you_sure');?>');" class="btn" type="submit" name="dellink" id="button" value="<?php echo lang_show('delete');?>">			   </td>
		   		<td colspan="6">&nbsp;<div class="page"><?php echo $pages?></div></td>
            </tr>
	  </table>  
  </form>
</div>
</div>
</body>
</html>