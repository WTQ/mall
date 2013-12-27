<?php
	include_once("$config[webroot]/module/shop/includes/plugin_credit_class.php");
	$credit=new credit();
	
	
	//获取当前用户信誉详情
	$arr = array('1','0','-1');
	$date=date("Y-m-d");
	$t=strtotime($date);
	$arr_time = array($t-3600*24*7,$t-3600*24*30,$t-3600*24*30*6);
	$type="seller";
	foreach($arr as $k=>$goodbad)
	{
		foreach($arr_time as $n=>$time)
		{
			$de[$k][$n]=$credit->get_user_comment_goodbad_count($type,$goodbad,$time,'>');
		}
		$de[$k][3]=$credit->get_user_comment_goodbad_count($type,$goodbad,$arr_time[2],'<=');
	}
	$tpl->assign("de",$de);
	$tpl->assign("count",$credit->get_user_comment_goodbad_count($type));
	//获取当前用户评论
	$sql="select id,goodbad,uptime,con,user,userid,pid,pname,fromid as uid,price from ".PCOMMENT." where fromid='$_GET[uid]' and puid = '$_GET[uid]' order by uptime desc";
	/**************************************/
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$db->query($sql);
	$comment["page"]=$page->prompt();
	/**************************************/
	$comment["list"]=$db->getRows();
	$tpl->assign("comment",$comment);
	//获取卖家用户商铺动态评分
	$sql="select avg(item1) as a,avg(item2) as b,avg(item3) as c,avg(item4) as d from ".UCOMMENT." where byid = '$buid'";
	$db->query($sql);
	$u=$db->fetchRow();
	$u['aw']=$u['a']/5*100;
	$u['bw']=$u['b']/5*100;
	$u['cw']=$u['c']/5*100;
	$u['dw']=$u['d']/5*100;
	$tpl->assign("u",$u);
	
	
//------------------------------------Seo config
$shopconfig["hometitle"]="信用评价".'-'.$shopconfig["hometitle"];
$shopconfig["homedes"]="信用评价".','.$shopconfig["homedes"];
$shopconfig["homekeyword"]="信用评价".','.$shopconfig["homekeyword"];
//====================================================================
$tpl->assign("lang",$lang);
$tpl->assign("config",$config);
$tpl->assign("com",$company);
$output=tplfetch("space_credit.htm",$flag);
?>