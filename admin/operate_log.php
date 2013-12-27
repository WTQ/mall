<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("../lang/" . $config['language'] . "/admin.php");
include_once("auth.php");
//====================================
//-------清空一个月前的日志
$st=time()-30*24*3600;
$sqlk="delete from ".OPLOG." where time<='$st'";
$db->query($sqlk);

		
if(!empty($_POST['delete']))
{
  if($_SESSION["ADMIN_TYPE"]=="1")
	{
		if(!empty($_POST["checktag"])&&is_array($_POST["checktag"]))
		{
		  $delid=implode(",",$_POST["checktag"]);
		  $sql="delete from ".OPLOG."  where  id in (".$delid.")";
		  $db->query($sql);	
		  msg("operate_log.php");
		}
	}
	else  
	{
		echo lang_show('noright');
		die;
	}
}	
if(!empty($_POST['beforetime'])&&!empty($_POST['delbetime']))
{
	if($_SESSION["ADMIN_TYPE"]=="1")
	{
		$st=strtotime($_POST['sbeforetime']);
		$et=strtotime($_POST['beforetime']);
		$sqlk="delete from ".OPLOG." where time>='$st' and time<='$et'";
		$db->query($sqlk);
	}
	else
	{
		echo lang_show('noright');
		die;
	}
}
//----------------------------------------
$psql='';
if(!empty($_GET["username"]))
	$psql.=" and user='$_POST[username]'";
if(!empty($_GET["actime"]))
{
	$st=strtotime($_GET['actime'])-86400;
	$et=$st+172800;
	$psql.=" and time>$st and time<$et";
}
if(!empty($_GET['url']))
	$psql.=" and url like '$_GET[url]%'";
	
$sql="select * from ".OPLOG."  where 1 $psql ";
//=============================
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  order by time desc limit ".$page->firstRow.",20";
	$pages = $page->prompt();
//=====================
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('user_admin');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
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
function checktime()
{
	  	if (document.oplogd.beforetime.value=="")
		{ 
         alert("<?php echo lang_show('timeerror');?>") 
         return false;  
        }  
     else 
		 {return true;} 
}
function checkdelc() 
{
	 var box_l = document.getElementsByName("checktag[]").length;
	 var count=0;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("checktag[]")[j].checked==false)
	  	  count++;
	 }
	  if(count==box_l)
	 {
		alert("<?php echo lang_show('fchecked');?>");
		return false;
	 }
	 else
	 {
		 if(confirm("<?php echo lang_show('suerdelit');?>"))
		 {
			 return true;
		 }
		 else
			 return false;
	 }
} 
</script>
<div class="bigbox" <?php if($_GET['act']=='add') echo 'style="display:block;"';else echo 'style="display:none;"';?> >
<div class="bigboxhead tab" style=" width:90%">
	<span class="cbox"><a href="operate_log.php"><?php echo lang_show('logrec');?></a></span>
	<span class="cbox on"><a href="#"><?php echo lang_show('beforetime');?></a></span>
</div>
<div class="bigboxbody" style="margin-top:-1px;">

<form name="oplogd" action="" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td align="left">
		<script language="javascript">
		var cdr = new Calendar("cdr");
		document.write(cdr);
		cdr.showMoreDay = true;
		</script>
		<?php echo lang_show('beforetime');?>:
		<input onFocus="cdr.show(this);" name="sbeforetime" type="text" id="sbeforetime" class="ltext" maxlength="30"> --  
		<input onFocus="cdr.show(this);" name="beforetime" type="text" id="beforetime" class="rtext" maxlength="30">
		<input class="btn" type="submit" name="delbetime" id="delbetime" value="<?php echo lang_show('delete');?>" <?php if($_SESSION["ADMIN_TYPE"]!="1") echo 'disabled';?> onClick="return checktime();">
	</td>
	</tr>
</table>
</form>  
</div>
</div>


<div class="bigbox" <?php if($_GET['act']=='') echo 'style="display:block;"';else echo 'style="display:none;"';?> >
<div class="bigboxhead tab" style=" width:90%">
	<span class="cbox on"><a href="#"><?php echo lang_show('logrec');?></a></span>
	<span class="cbox"><a href="operate_log.php?act=add"><?php echo lang_show('beforetime');?></a></span>
</div>
<div class="bigboxbody" style="margin-top:-1px;">
	
	<table width="100%" height="184" border="0" cellpadding="0" cellspacing="0" style="word-break:break-all">

        <tr>
          <td colspan="5" align="left" >
		  <form name="oplog" action="" method="GET">
		  <?php echo lang_show('uname');?>:&nbsp;
            <input name="username" type="text" id="username" size="20" maxlength="20" value="<?php if(!empty($_GET['username'])) echo $_GET['username'];?>">
            <?php echo lang_show('actime');?>
            <input onFocus="cdr.show(this);" name="actime" type="text" id="actime" size="20" maxlength="30" value="<?php if(!empty($_GET['actime'])) echo $_GET['actime'];?>">
            操作URL:
            <input type="text" name="url" value="<?php if(!empty($_GET['url'])) echo $_GET['url'];?>">
            <input class="btn" type="submit" name="research" id="research" value="<?php echo lang_show('query');?>">
			(注意：后台日志仅保留一个月)
			</form>
		  </td>
        </tr>
		<form name="doplog" action="" method="post">
        <tr class="theader">
          <td width="4%" align="left"><input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()"></td>
          <td width="17%" align="left"  ><?php echo lang_show('actuser');?></td>
          <td width="18%" align="left"  ><?php echo lang_show('act');?></td>
          <td width="41%" align="left"  ><?php echo lang_show('acturl');?></td>
          <td width="20%" align="left"  ><?php echo lang_show('actime');?></td>
        </tr>
        <?php
	      foreach ($re as $v)
          {
			  $scrp_name=substr($v['scriptname'],0,-4);
			  if($scrp_name=='main')
				  $lang[$scrp_name]=lang_show('logo');
        ?>
        <tr>
          <td align="left" ><input type="checkbox" class="checkbox" name="checktag[]" id="checktag[]" value="<?php echo $v['id'];?>"> </td>
          <td align="left" ><?php echo $v['user'];?></td>
          <td align="left" ><?php echo $lang[$scrp_name];?></td>
          <td align="left" ><?php echo $v['url'];?></td>
          <td align="left" >
		  <?php 
			if($config['language']!='en')	
				echo date("Y-m-d H:m:s",$v['time']);
			else 
				echo date("m/d/Y H:m:s",$v['time']);
		?></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td colspan="3" align="left"  ><input class="btn" type="submit" name="delete" id="delete" value="<?php echo lang_show('delch');?>" onClick="return checkdelc();" <?php if($_SESSION["ADMIN_TYPE"]!="1") echo 'disabled';?>></td>
          <td colspan="2" align="right"  ><div class="page"><?php echo $pages?></div></td>
        </tr>
		</form>
      </table>
	</div>
</div>
</body>
</html>