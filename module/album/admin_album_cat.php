<?php
include_once("module/album/includes/plugin_album_class.php");
include_once("includes/page_utf_class.php");
$album=new album();
//==========================================		
$act=isset($_POST['submit'])?$_POST['submit']:NULL;
//----------add .edit
if($act=="add")
{
	$re=$album->add_album_cat(6);
	if($re)
		$admin->msg("main.php?m=album&s=admin_album_cat");
}
else if($act=="edit")
{
	$album->edit_album_cat(6,$_POST['editid']);
	$admin->msg("main.php?m=album&s=admin_album_cat");
}

//---------delete
if(!empty($deid))
{
	$album->del_album_cat(6,$_GET['deid']);
	$admin->msg("main.php?m=album&s=admin_album_cat");
}
//-----detail
if(isset($_GET['edit']))
{
	$de=$album->get_album_cat_list(6,$_GET['edit']);
	if(strlen($de['sys_cat'])>6)
		$de['scatid']=$de['sys_cat'];
	if(strlen($de['sys_cat'])>4)
		$de['tcatid']=substr($de['sys_cat'],0,6);
	$de['catid']=substr($de['sys_cat'],0,4);
	$tpl->assign("re",$de);
} 
else
{
	$tpl->assign("re",$album->get_album_cat_list(6,0));
}
//==================================================		
$tpl->assign("config",$config);
$tpl->assign("lang",$lang);
$output=tplfetch("admin_album_cat.htm")
?>