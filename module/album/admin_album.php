<?php
include_once("$config[webroot]/module/album/includes/plugin_album_class.php");
include_once("includes/page_utf_class.php");
$album=new album();
$act=isset($_POST['action'])?$_POST['action']:NULL;
//================================================================
if(isset($_GET['editid']))
	$tpl->assign("re",$album->album_detail($_GET['editid']));
//------------------------------上传图片--------------------------
if($act=="submit")
{							
	if(isset($_POST['up_type']) && $_POST['up_type']=='multi')
	{
		if($strid=$album->add_multi_album())
		{
			$head=isset($_GET['nohead'])?'&nohead='.$_GET['nohead']:'';
			if(isset($_POST['album_custom_cat']))
				msg("main.php?m=album&s=admin_album&info=1&strid=$strid&catid=$_POST[album_custom_cat]".$head);
			else
				msg("main.php?m=album&s=admin_album&info=1&strid=$strid".$head);
		}
	}
	else
	{
		$re=$album->add_album();
		if($re)
			msg("main.php?action=admin_album&info=1");
	}
}
//------------------------------编辑图片-----------------
if($act=="edit")
{
	$album->edit_album();
	msg("main.php?m=album&s=admin_album&info=2&catid=$_GET[catid]");
}
//-----------------------------设为封面------------------
if($_GET['set_cover'])
{
	
	$sql="select pic from ".ALBUM." where id='$_GET[set_cover]'";
	$db->query($sql);
	$img=$db->fetchField('pic');
	
	$sql="update ".CUSTOM_CAT." set pic='$img' where id='$_GET[catid]'";
	$db->query($sql);
	$admin->msg('main.php?m=album&s=admin_album_cat');
}
//-----------------------------删除图片------------------
if(!empty($_GET['deid'])&&is_numeric($_GET['deid']))	
	$album->del_album($_GET['deid'],$buid);
//-----------------------------获取相册------------------
$tpl->assign("album_custom_cat",$admin->get_custom_cat_list(6,0));
//-----------------------------获取相册下面的所有图片----
$tpl->assign("de",$album->album_list($_GET['catid']));
//=======================================================
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
if(!empty($_GET['nohead']))
{
	$output=tplfetch("admin_iframe_album.htm",'','true');
	die;
}
else
{	
	$output=tplfetch("admin_album.htm");
}
?>