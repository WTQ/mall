<?php
include_once("../includes/page_utf_class.php");
//===================================================
$submit=isset($_POST['submit'])?$_POST['submit']:NULL;
@$id=implode(",",$_POST['chk']);
if(!empty($_GET['did']))
{
	$id=$_GET['did'];
}
if(!empty($id))
{
	if($submit==lang_show('del') or !empty($_GET['did']))
	{
	   $sql="delete from ".NEWSVOTE." where id in ($id)";
	}
	if($submit==lang_show('bres'))
	  $sql="update ".NEWSVOTE." set type=1 where id in ($id)";
	if($submit==lang_show('nbres'))
	  $sql="update ".NEWSVOTE." set type=0 where id in ($id)";
	 $db->query($sql);
}
if($submit==lang_show('search'))
{
	$key=trim($_POST['key']);
	
	if(!empty($key))
	{
		$str.=" and title like '%$key%'";
	}
}
if($_GET['o']==1)
$order=" order by id";
else if($_GET['o']==2)
$order=" order by num DESC";
else if($_GET['o']==3)
$order=" order by num";
else if($_GET['o']==4)
$order=" order by uptime DESC";
else if($_GET['o']==5)
$order=" order by uptime";
else if($_GET['o']==6)
$order=" order by votetype DESC";
else if($_GET['o']==7)
$order=" order by votetype";
else if($_GET['o']==8)
$order=" order by limitip DESC";
else if($_GET['o']==9)
$order=" order by limitip";
else
$order=" order by id DESC";

$sql="select * from ".NEWSVOTE." a where 1 $str ".$order;
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$pages = $page->prompt();
$db->query($sql);
$re=$db->getRows();
foreach($re as $key=>$val)
{
	$str=explode('|',$val['votetext']);
	//$re[$key]['num']=0;
	for($i=0;$i<count($str);$i++)
	{
		$re[$key]['item'][$i]=explode(',',$str[$i]);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />

<style>
.bigboxbody{ border:none !important;}
.bigboxbody .v{ text-align:left;}
.bigboxbody .r{ text-align:right; padding-right:1%;}
.bigboxbody .v span{ color:#FF0000;}
.bigboxbody .v a{ text-decoration:none }
.bigboxbody select{ width:120px;} 
.bigbox tr{ background:#ffffff}
.class_2{background:#fefbc6 !important;cursor:hand;}
.class_3{background:#FFCC99 !important;cursor:hand;}
</style>
</HEAD>
<script src="<?php echo $config['weburl']; ?>/script/jquery-1.4.4.min.js" type="text/javascript"></script>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("chk[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("chk[]")[j].checked==true)
	  	{
			document.getElementsByName("chk[]")[j].checked = false;
			document.getElementById('tr'+j).className='class_1';
		}
		else
		{
			document.getElementsByName("chk[]")[j].checked = true;
			document.getElementById('tr'+j).className='class_3';
		}
	 }
}
</script>

<body>
<form method="post" action="" enctype="multipart/form-data" onSubmit="return checkval(this)">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('vote'); ?></div>
	<div class="bigboxbody">
	  <form action="" method="post"> 
	   <table border="0" cellpadding="0" cellspacing="0" width="100%">
	      <tr>
		     <td colspan="2">
			 	<input class="btn" type="button" value="<?php echo lang_show('add'); ?>" name="add" id="add" onClick="window.location.href='module.php?m=vote&s=vote.php'">
			 </td>
			 <td colspan="6" class="r">
			  <?php echo lang_show('search');?>
			  <input type="text" name="key" id="key" size="30">
			  <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('search') ?>" />
			 </td>
		  </tr>
		 
          <tr class="theader">
		     <td colspan="2"><a href="module.php?m=vote&s=vote_list.php&o=<?php if($_GET['o']==1) echo ""; else echo "1"; ?>"><?php echo lang_show('id'); ?></a></td>
			 <td><?php echo lang_show('title'); ?></td>
			 <td width="10%"><a href="module.php?m=vote&s=vote_list.php&o=<?php if($_GET['o']==3) echo "2"; else echo "3"; ?>"><?php echo lang_show('click');?></a></td>
             <td width="10%"><a href="module.php?m=vote&s=vote_list.php&o=<?php if($_GET['o']==7) echo "6"; else echo "7"; ?>"><?php echo lang_show('type'); ?></a></td>
             <td width="10%"><a href="module.php?m=vote&s=vote_list.php&o=<?php if($_GET['o']==9) echo "8"; else echo "9"; ?>"><?php echo lang_show('limitip'); ?></a></td>
			 <td width="10%"><a href="module.php?m=vote&s=vote_list.php&o=<?php if($_GET['o']==5) echo "4"; else echo "5"; ?>"><?php echo lang_show('time'); ?></a></td>
			 <td width="10%"><?php echo lang_show('operation'); ?></td>
		  </tr>
		
          <?php foreach($re as $key=>$val) { ?>
		   <tr id="tr<?php echo $key;?>" onMouseOver="this.className='class_2'" onMouseOut="if(document.getElementById('chk<?php echo $key ?>').checked) this.className='class_3'; else this.className='class_1'" >
		     <td width="3%">
			 	<input type="checkbox" class="checkbox" name="chk[]" id="chk<?php echo $key ?>" value="<? echo $val['id']?>">
			 </td>
			 <td width="5%"><?php echo $val['id'] ?></td>
			 <td class="v">
			 <?php if($val['type']==1) echo '<span>['.lang_show('rec').']</span>';?>
             <?php if(strtotime($val['time'])-time()<0 and strtotime($val['time'])) echo "<span>[结束]</span>"; ?>
			 <?php echo $val['title'] ?>
             </td>
			 <td><?php echo $val['num']; ?></td>
             <td><?php foreach(lang_show('vote_types') as $key=>$list){ if($key==$val['votetype']) { echo $list; } } ?></td>
             <td><?php foreach(lang_show('limitips') as $key=>$list){ if($key==$val['limitip']) { echo $list; } } ?></td>
			 <td><?php echo date('Y-m-d',$val['uptime']); ?></td>
             <td>
			 	<a href="module.php?m=vote&s=vote.php&vid=<?php echo $val['id'];?>"><img src="<?php echo $config['weburl']; ?>/image/admin/edit.gif"></a>
			    <a onClick="return confirm('确认要删除？');" href="module.php?m=vote&s=vote_list.php&did=<?php echo $val['id'];?>"><img src="<?php echo $config['weburl']; ?>/image/admin/del.gif"></a>
			 </td>
		  </tr>
		  <?php } ?>
		  
		  <tr>
		    <td ><input type="checkbox" class="checkbox" onClick="do_select()" name="chkall" id="chkall" > </td>
		    <td colspan="4" class="v">
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('del') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('bres') ?>" />
            <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('nbres') ?>" />
			</td>
			<td colspan="3"><?php echo $pages?></td>
		  </tr>
	   </table>
	  </form> 
	</div>
</div>
</body>
</html>
