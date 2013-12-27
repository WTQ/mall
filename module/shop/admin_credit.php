<?php
	include_once("$config[webroot]/module/shop/includes/plugin_credit_class.php");
	$credit=new credit();
	
	//删除中评 差评
	if(is_numeric($_GET['id']))
	{
		$sql="select goodbad,fromid from ".PCOMMENT." where id='$_GET[id]'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re)
		{
			if($re['goodbad']=="-1")
			{
				if($_SESSION['USER_TYPE']==2)
				{
					$sql="update ".ALLUSER." set buyerpoints=buyerpoints+1 where userid='$re[fromid]'";
				}
				else
				{
					$sql="update ".ALLUSER." set sellerpoints=sellerpoints+1 where userid='$re[fromid]'";
				}
				$db->query($sql);
			}
			$sql="delete from ".PCOMMENT." where id='$_GET[id]'";
			$db->query($sql);	
			$admin->msg("main.php?m=shop&s=admin_credit&type=1");
		
		}
		else
		{
			$admin->msg("main.php?m=shop&s=admin_credit&type=1",'数据不存在','failure');	
		}
	}
	
	//获取当前用户 卖家信誉(sellerpoints) 买家信誉(buyerpoints)
	$tpl->assign("credit",$credit->get_user_credit());	
	//获取当前用户信誉详情
	$arr = array('1','0','-1');
	$date=date("Y-m-d");
	$t=strtotime($date);
	$arr_time = array($t-3600*24*7,$t-3600*24*30,$t-3600*24*30*6);
	$type=$_SESSION['USER_TYPE']==1?"buyer":"seller";
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
	if($_SESSION['USER_TYPE']==2)
	{
		if(!isset($_GET['type']))
			$sql="select id,goodbad,uptime,con,user,userid,pid,pname,fromid as uid,price from ".PCOMMENT." where fromid='$buid' order by uptime desc";
		else
			$sql="select id,goodbad,uptime,con,user,userid,pid,pname,fromid as uid,price from ".PCOMMENT." where userid='$buid' order by uptime desc";
	}
	else
	{
		if(!isset($_GET['type']))
			$sql="select id,goodbad,uptime,con,user,userid,pid,pname,fromid as uid,price from ".PCOMMENT." where fromid='$buid' order by uptime desc";
		else
			$sql="select id,goodbad,uptime,con,user,userid,pid,pname,fromid as uid,price from ".PCOMMENT." where userid='$buid' order by uptime desc";	
	}
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
	$re["page"]=$page->prompt();
	/**************************************/
	$re["list"]=$db->getRows();
	$tpl->assign("re",$re);
	//获取卖家用户商铺动态评分
	if($_SESSION['USER_TYPE']==2)
	{
		$sql="select avg(item1) as a,avg(item2) as b,avg(item3) as c,avg(item4) as d from ".UCOMMENT." where byid = '$buid'";
		$db->query($sql);
		$u=$db->fetchRow();
		$u['aw']=$u['a']/5*100;
		$u['bw']=$u['b']/5*100;
		$u['cw']=$u['c']/5*100;
		$u['dw']=$u['d']/5*100;
		$tpl->assign("u",$u);
	}
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_credit.htm");
?>