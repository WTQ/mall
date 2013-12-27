<?php

include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=========================================

	if($_GET['t']=="sp")
	{
		$sql="select * from ".SPE." where id='$_GET[cid]'";
		$db->query($sql);
		$de=$db->fetchRow();
		$title=$de['name'];
		$tit="<a href='$config[weburl]/?m=special&s=spd&name=$de[file_name]'>$title</a>";
		$votes=$_GET['id'];
	}
	else
	{
		if(!empty($_GET['id']))
		{
			$sql="SELECT ftitle,title,nid,vote FROM ".NEWSD." WHERE nid='$_GET[id]'";
			$db->query($sql);
			$de=$db->fetchRow();
			$title=$de['title'];
			if(!empty($de['ftitle']))
			$title=$de['ftitle'];
			$tit="<a href='$config[weburl]/?m=news&s=newsd&id=$de[nid]'>$title</a>";
			$votes=substr($de['vote'],0,-1);
		
		}
		elseif(!empty($_GET['vid']))
		{
			$title="调查结果";
			$tit="";
			$votes=$_GET['vid'];
		}
	}
	$sql="select * from ".NEWSVOTE." where id in ($votes)";	
	$db->query($sql);
	$vote=$db->getRows();
	foreach($vote as $key=>$val)
	{
		$str=explode('|',$val['votetext']);
		for($i=0;$i<count($str);$i++)
		{
			$vote[$key]['item'][$i]=explode(',',$str[$i]);
		}
	}
	
	$tpl->assign("title","$title");
	$tpl->assign("tit",$tit);
	$tpl->assign("vote",$vote);
	
	include_once("footer.php");
	$out=tplfetch("vote.htm",$flag);
?>