<?php

include_once("module/album/includes/plugin_album_class.php");
include_once("includes/page_utf_class.php");
$album=new album();
//=======================================================
	$act=isset($_POST['submit'])?$_POST['submit']:NULL;
	if($act)
	{
		$re=$album->add_album();
		header("Location: $config[weburl]/?m=album&s=upload");
	}
	$submit=isset($_POST['act'])?$_POST['act']:NULL;
	if($submit)
	{
		$re=$album->add_album();
	}
//-----------------------------获取相册下面的所有图片----
	if(!empty($_SESSION["ADMIN_USER"]))
		$sql="select * from ".ALBUM." where userid='0' or userid='$buid'";
	else
		$sql="select * from ".ALBUM." where userid='$buid'";
	//-----------------------------
	$page = new Page;
	$page->listRows=18;
	if (!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= " limit ".$page->firstRow.",".$page->listRows;
	//-----------------------------
	$db->query($sql);
	$are["list"]=$db->getRows();
	$are["page"]=$page->prompt();
	$tpl->assign("de",$are);
//=======================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("album_upload.htm",'','true');
die;
?>