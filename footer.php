<?php
//-----------------------------------------------------------------
$fn=basename($_SERVER['SCRIPT_FILENAME']);
if($fn!='main.php'&&$fn!='shop.php')
	include_once($config['webroot'].'/lang/'.$config['language'].'/front.php');
if(!empty($_GET['m']))
	@include('module/'.$_GET['m'].'/lang/'.$config['language'].'.php');

$tpl->assign("lang",$lang);
$tpl->assign("sname",$fn);
//-----------------------------------------------------------------

$sql="select * from ".WEBCON." where con_statu=1 and lang='$config[language]' order by con_no asc";
$db->query($sql);
while($v=$db->fetchRow())
{
	if(!empty($v['con_linkaddr']))
	{
		if(substr($v['con_linkaddr'],0,4)=='http')
			$url=$v['con_linkaddr'];
		else
			$url=$config['weburl'].'/'.$v['con_linkaddr'];
	}
	else
		$url=$config['weburl']."/aboutus.php?type=".$v['con_id'];
	$li[]="<a href='$url'>".$v['con_title']."</a>";
}
if(isset($li))
	$tpl->assign("web_con",implode(" | ",$li));
//------------------------------------------------------------------
if(!empty($config['copyright']))
{
	$config['copyright'].='<br />Powered by <a href="http://mall.te168.cn">简爱</a>';
	$tpl->assign("bt",$config['copyright']);
}
$tpl->assign("config",$config);
//==================================================================
if($config['rewrite']>0)
{
	if($config['rewrite']==1)
	{
		$searcharray[] = "/\/\?m=(\w+)&s=(\w+)&id=(\w+)/";
		$searcharray[] = "/\/\?m=(\w+)&s=(\w+)/";
		$searcharray[] = "/\/\?m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)&action=(\w+)&id=(\w+)&m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)&action=(\w+)&m=(\w+)/";
		$searcharray[] ="/shop\.php\?uid=(\w+)/";

		$replacearray[] = "/\\1-\\2-\\3.html";
		$replacearray[] = "/\\1-\\2.html";
		$replacearray[] = "/\\1.html";
		$replacearray[] = "shop-\\1-\\2-\\3-\\4.html";
		$replacearray[] = "shop-\\1-\\2-\\3.html";
		$replacearray[] = "shop-\\1.html";
	}
	function rewrite($output, &$smarty)
	{
		global $searcharray,$replacearray;
		return preg_replace($searcharray, $replacearray, $output);
	}
	$tpl->register_outputfilter("rewrite");
}
if(isset($mlang))
	$tpl->register_outputfilter("translate");
?>