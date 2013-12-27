<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================
if(!empty($_POST['addvercode']))
{
	$sql="insert into ".REGVERFCODE." (question,answer) VALUES ('$_POST[verquestion]','$_POST[veranswer]')"; 
	$db->query($sql);
}
if(!empty($_POST['action'])&&$_POST['action']=='delete'&&!empty($_POST['id']))
{
	$sql="delete from  ".REGVERFCODE." where id=".$_POST['id']; 
	$db->query($sql);
}
if(!empty($_POST['delete']))
{
	if(is_array($_POST["checkip"]))
	{
		$deltn=implode(",",$_POST["checkip"]);
		$sql="delete from  ".REGVERFCODE." where id in($deltn)"; 
		$db->query($sql);	
	}
}
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
	 for(var j = 0 ; j < document.getElementsByName("checkip[]").length; j++)
	 {
	  	if(document.getElementsByName("checkip[]")[j].checked==true)
	  	  document.getElementsByName("checkip[]")[j].checked = false;
		else
		  document.getElementsByName("checkip[]")[j].checked = true;
	 }
}
function checkcontent()
{
	if (document.regvercode.verquestion.value==""||document.regvercode.veranswer.value=="")
	{ 
		alert("<?php echo lang_show('quesnull');?>") 
		return false;  
	}  
	else 
		return true;
}
</script>
<div class="bigbox">
	<div class="bigboxhead">增加验证问题</div>
	<div class="bigboxbody">
<form name="regvercode" action="user_reg_verf.php" method="POST">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="searh_left" align="left" >
             <?php echo lang_show('regques');?></td>
          <td align="left" ><input name="verquestion" type="text" id="verquestion" class="text" maxlength="40" /></td>
        </tr>
        <tr>
          <td align="left" ><?php echo lang_show('quesan');?></td>
          <td align="left" ><input name="veranswer" type="text" id="veranswer" class="text" maxlength="35" /></td>
        </tr>
        <tr>
          <td align="left" >&nbsp;</td>
          <td align="left" ><input class="btn" type="submit" name="addvercode" id="addvercode" value="<?php echo lang_show('addques');?>" onclick="return checkcontent();" /></td>
        </tr>
      </table>
	</form>
	</div>
</div>

<div class="bigbox" style="margin-top:20px;">
	<div class="bigboxhead"><?php echo lang_show('ureg_ques');?></div>
	<div class="bigboxbody">
<form name="regvercode" action="user_reg_verf.php" method="POST">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="theader">
          <td align="leftr" width="25" >
          <input type="checkbox" class="checkbox" name="checkipall" id="checkipall" onClick="checkall()">    
          </td>
            <td width="*%">
			<?php echo lang_show('regques');?></td>
          <td width="398" align="left" ><?php echo lang_show('quesan');?></td>
        </tr>
        <?php
           unset($sql);
		   $sql="select * from ".REGVERFCODE;
		   $page = new Page;
	       $page->listRows=20;
	       if (!$page->__get('totalRows'))
		   {
		       $db->query($sql);
		       $page->totalRows = $db->num_rows();
	        }
	       $sql .= "  limit ".$page->firstRow.",20";
	       $pages = $page->prompt();   
           $db->query($sql);
           $re=$db->getRows();
           foreach($re as $v)
	       {
        ?>
        <tr>
          <td width="25" align="left" ><input type="checkbox" class="checkbox" name="checkip[]" value="<?php echo $v['id'];?>">
           </td>
           <td width="*%">
            <?php echo $v['question']; ?></td>
          <td align="left" ><?php echo $v['answer']; ?></td>
       </tr>
	   <?php
		   }
		?>
		 <tr>
          <td align="left" width="25" colspan="2" ><input class="btn" type="submit" name="delete" id="delete" value="<?php echo lang_show('delete');?>"></td>
          <td align="left" width="300" ><div class="page"><?php echo $pages?></div></td>
        </tr>
      </table>
	</form>
	</div>
</div>
</body>
</html>