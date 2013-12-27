<?php

//===========================购买记录
$sql="select  a.name,b.user,b.buyerpoints,a.price,a.num,a.time 
from ".ORPRO." a left join ".ALLUSER." b on a.buyer_id=b.userid where a.pid='$_GET[id]'";
//---------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$buy_rec["page"]=$page->prompt();
//---------------
$buy_rec["list"]=$db->getRows();
$tpl->assign("buy_rec",$buy_rec);

//=============================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid 
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id 
where a.pid='$_GET[id]' and b.userid='$_GET[uid]' and a.userid <> '$_GET[uid]' ";
//---------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review["page"]=$page->prompt();
//--------------
$review["list"]=$db->getRows();
$tpl->assign("review",$review);

//================================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid 
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id 
where a.pid='$_GET[id]' and b.userid='$_GET[uid]' and a.userid <> '$_GET[uid]' and a.goodbad='1' ";
//-----------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review2["page"]=$page->prompt();
//----------------
$review2["list"]=$db->getRows();
$tpl->assign("review2",$review2);

//===============================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid 
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id 
where a.pid='$_GET[id]' and b.userid='$_GET[uid]' and a.userid <> '$_GET[uid]' and a.goodbad='0' ";
//----------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review3["page"]=$page->prompt();
//----------------
$review3["list"]=$db->getRows();
$tpl->assign("review3",$review3);

//================================用户评论
$sql="select a.goodbad,a.uptime,a.con,a.user,a.userid,a.pid,b.userid as uid 
from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id 
where a.pid='$_GET[id]' and b.userid='$_GET[uid]' and a.userid <> '$_GET[uid]' and a.goodbad='-1' ";
//------------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);
$review4["page"]=$page->prompt();
//-----------------
$review4["list"]=$db->getRows();
$tpl->assign("review4",$review4);

//=====================================
$sql="select avg(item1) as a from ".UCOMMENT." where byid = '$_GET[uid]'";
$db->query($sql);
$u=$db->fetchRow();
$u['aw']=$u['a']/5*100;
$tpl->assign("u",$u);	
//====================================产品详情

$tpl->assign("de",$prode);

//====================================SEOconfig
$shopconfig["hometitle"]=$prode['pname'].'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]=$prode['keywords'].','.$shopconfig["homedes"];
$shopconfig["homekeyword"]=$prode['keywords'].','.$shopconfig["homekeyword"];
//---------------------------------------
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_product_detail.htm",$flag);
?>