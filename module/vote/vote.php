<?php
include_once("includes/global.php");
//=====================================
$_GET['id']*=1;

if($_POST['type']=="special")
{	
	$votes=implode(',',$_POST['vote']);
	$url=$votes."&t=sp&cid=$_POST[cid]";
}
else
{
	if(!empty($_GET['id']))
	{
		$sql="select vote from ".NEWSD." where nid='$_GET[id]'";
		$db->query($sql);
		$votes=substr($db->fetchField('vote'),0,-1);
		$url=$_GET['id'];
	}
	elseif(!empty($_POST['vid']))
	{
		$votes=implode(',',$_POST['vid']);
		$url="&vid=$votes";
	}
	else
	{
		die("forbiden");
	}
}

if(!empty($votes))
{
	$sql="select * from ".NEWSVOTE." where id in ($votes)";	
	$db->query($sql);
	$vote=$db->getRows();
	foreach($vote as $key=>$val)
	{
		if($val['limitip']==1)
		{
			setcookie("vote".$buid.$val['id'],"1");
		}
		$votetext='';
		$num=$_POST['vote'.$val['id']];
		$str=explode('|',$val['votetext']);
		$counts=$val['num'];
		for($i=0;$i<count($str);$i++)
		{
			$item[$i]=explode(',',$str[$i]);
		}
	
		foreach($item as $k=>$list)
		{
			if(@in_array(($k+1),$num))
			{
				$list[1]=$list[1]+1;
				$counts=$counts+1;
			}
			$votetext.=$list[0].",".$list[1]."|";
			//echo $votetext."<br>";
		}
		$sql="update ".NEWSVOTE." set votetext='".substr($votetext,0,-1)."',num='$counts' where id=".$val['id'];
		$db->query($sql);
	}
	header("location:$config[weburl]/?m=vote&id=$url");
}
?>