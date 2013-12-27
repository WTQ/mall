<?php
/**
 * powered by b2bbuilder
 * Copyright http://www.b2b-builder.com
 * Auther:brad zhang;
 * Des:Reveiw
 */
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//===============================================
if (!empty($_GET['ctype']))
{
	if ($_GET['ctype']==1)
	   $sql="select title from ".NEWSD." where  nid=".$_GET['conid'];
	if ($_GET['ctype']==3)
	   $sql="select pname as title from ".PRO." where   id=".$_GET['conid'];
	if ($_GET['ctype']==4)
	   $sql="select title from ".EXHIBIT." where  id=".$_GET['conid'];
	if ($_GET['ctype']==5)
	   $sql="select downname as title from ".down." where  id=".$_GET['conid'];
	if ($_GET['ctype']==7)
		   $sql="select projecttitle as title from ".PROJECT." where  id=".$_GET['conid'];
	$db->query($sql);
	$titlemsg=$db->fetchRow();
	$titlem=$titlemsg['title'];
	$tpl->assign("tmsg",$titlem);
}  
$sql="select a.*,b.user from ".COMMENT." a left join ".ALLUSER." b on a.fromuid=b.userid
     where a.conid=".intval($_GET['conid'])." and a.ctype=".intval($_GET['ctype']);
//---------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows'))
{
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
//-----------------------------
$db->query($sql);
$review["page"]=$page->prompt();
$review["list"]=$db->getRows();
$tpl->assign("revdetail",$review);
include_once("footer.php");
$tpl->display("rewiew_detail.htm",$flag)
?>