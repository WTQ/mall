<?php
//===========公告页========================
$id=empty($_GET['id'])?NULL:$_GET['id'];
$nt=strtotime(date("Y-m-d"));

$sql="select * from ".ANNOUNCEMENT." where status>0 order by displayorder , id desc";
$db->query($sql);
$notlist=$db->getRows();
$tpl->assign("noticlist",$notlist);

if(empty($id))
	$id=$notlist[0]['id'];
$sql="select * from ".ANNOUNCEMENT." where id='$id'";
$db->query($sql);
$de=$db->fetchRow();
$tpl->assign("noticecontent",$de);
//------------------------SEO----
$config['title']=$de['title'];
$config['keyword']=csubstr(strip_tags($de['content']),0,100);
$config['description']=csubstr(strip_tags($de['content']),0,100);
//========================================
include_once("footer.php");
$out=tplfetch("detail.htm");
?>