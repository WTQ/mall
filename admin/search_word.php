<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
@include_once("auth.php");
//====================================
if(!empty($_POST['keyword']))
{
	include('../lib/allchar.php');
	$ar=explode(',',$_POST['keyword']);
	foreach($ar as $v)
	{
		$v=trim($v);
		$char=c($v);		
		$sql="insert into ".SWORD." (keyword,char_index) value ('$v','$char') ";
		$db->query($sql);
	}
}
if(empty($_GET['deltag'])&&!is_array($_GET['id']))
{
	$sql="delete from ".SWORD." where id='".trim($_GET['id'])."'";
	$db->query($sql);
}
if($_GET['del']==lang_show('delete')&&is_array($_GET['id']))
{
	$deltn=implode(",",$_GET["id"]);
	$sql="delete from ".SWORD."  where  id in (".$deltn.")";
	$db->query($sql);
}

if(!empty($_GET['name']))
	$ssql=" and keyword like '%$_GET[name]%' ";
$sql="select * from ".SWORD." where 1 $ssql order by nums desc";
//=============================
$page = new Page;
$page->listRows=50;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",50";
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
	 for(var j = 0 ; j < document.getElementsByName("id[]").length; j++)
	 {
	  	if(document.getElementsByName("id[]")[j].checked==true)
	  	  document.getElementsByName("id[]")[j].checked = false;
		else
		  document.getElementsByName("id[]")[j].checked = true;
	 }
}
</script>
<div class="bigbox">
	<div class="bigboxhead">热门搜索词</div>
	<div class="bigboxbody">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2"  align="left">
		  <form action="" method="get">
            <input name="name" type="text" id="name" value="<?php if(!empty($_GET['name'])) echo $_GET['name'];?>" >
            <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('search');?>">
		  </form>
		  </td>
          <td  colspan="2" align="left" >
		  <form name="form1" method="post" action="">
            <input style="color:#999999;" onClick="this.value='';this.style.color='#000000';$('#tsubmit').val(1);" name="keyword" type="text" value="填加词，多个词用英文,号分隔。如：上海远丰,B2Bbuilder" size="60">
			<input id="tsubmit" name="tsubmit" type="hidden" value="">
            <input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>">
          </form>
          </td>
        </tr>
		<form name="iplockset" action="" method="GET">
        <tr class="theader">
          <td align="left"  width="25">
          <input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()">
          </td>
          <td>关键词</td>
          <td width="555" align="left">搜索次数</td>
          <td width="143" align="left">操作</td>
        </tr>
        <?php
	      foreach ($re as $v)
          {
        ?>
        <tr>
          <td align="left" >
          <input type="checkbox" class="checkbox" name="id[]" value="<?php echo $v['id'];?>"></td><td><?php echo $v['keyword'];?></td>
          <td align="left" ><?php echo $v['nums'];?></td>
          <td  align="left" ><a href="search_word.php?id=<?php echo $v['id'];?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"><?php echo $delimg;?></a></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td width="25"  align="left"  >
          <input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()">
          </td>
          <td><input class="btn" type="submit" name="del" value="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"></td>
          <td height="24" colspan="2" align="right">&nbsp;<div class="page"><?php echo $pages;?></div></td>
        </tr>
		</form>
	</table>
	</div>
</div>
</body>
</html>