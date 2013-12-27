<?php
include_once("../includes/page_utf_class.php");
//==========================
if(!empty($_GET['deid']))
{
	$sql="delete from ".SPE." where id='$_GET[deid]'";
	$db->query($sql);
	@unlink($config['webroot']."/uploadfile/newsimg/special/".$_GET['deid'].".jpg");//专题图片
	$sql="select * from ".MLAY." where tid='$_GET[deid]' and type=1 and name='picture'";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		$v=unserialize($v['filter']);
		$pic = $config['webroot']."/uploadfile/modules/".$v['pic'];
		@unlink($pic);
	}
	$sql="delete from ".MLAY." where tid='$_GET[deid]' and type=1";
	$db->query($sql);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
</HEAD>
<body>
<form action="" method="post" enctype="multipart/form-data">
<div class="bigbox">
	<div class="bigboxhead">增加专题</div>
	<div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class="theader">
        <td width="38%">专题名称</td>
        <td width="21%">有效期</td>
        <td width="16%">创建者</td>
        <td width="15%">创建时间</td>
        <td width="10%">管理</td>
      </tr>
	  <?php
      $sql="select * from ".SPE." order by id desc";
	  	//=============================
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
      $re=$db->getRows();
      foreach($re as $v)
      {
      ?>
  <tr>
    <td><?php echo $v['name'];?></td>
    <td><?php echo dateformat($v['stime']);?> / <?php echo dateformat($v['etime']);?></td>
    <td><?php echo $v['add_user'];?></td>
    <td><?php echo dateformat($v['add_time']);?></td>
    <td>
        <a href="module.php?m=special&s=special_con.php&id=<?php echo $v['id'];?>"><?php echo $setimg;?></a>
           <a href="module.php?m=special&s=add_special.php&edit=<?php echo $v['id'];?>"><?php echo $editimg;?></a>
           <a href="module.php?m=special&s=special_list.php&deid=<?php echo $v['id'];?>"><?php echo $delimg;?></a>     </td>
  </tr>
  <?php
  }
  ?>
</table>
	<div class="page"><?php echo $pages?></div>
    </div>
</div>
</form>
</body>
</html>