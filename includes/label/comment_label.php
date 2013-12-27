<?php
function comment($ar)
{
	global $config,$db,$tpl,$buid;
	
	$ar['temp']=empty($ar['temp'])?'notice_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
		
	if(!empty($_POST['reviewcon'])&&!empty($_POST['myrands']))
	{	
		session_start();
		if(strtolower($_SESSION["auth"])!=strtolower($_POST['myrands']))
			echo "<script>alert('验证码输入错误');</script>";
		else
		{
			$contents=htmlspecialchars($_POST['reviewcon']);
			$ip=getip();
			$buid=empty($buid)?0:$buid;
			$bname=$_COOKIE['USER'];
			
			$sql="insert into ".COMMENT." 
			(conid,fromuid,fromname,rank,ctype,content,uptime)
				VALUES 
			($ar[cid],'$buid','$bname','$_POST[rank]','$ar[ctype]','".$contents."',".time().")"; 
			$db->query($sql);
		}
	}
    //------------------------------------------------
	$sql="select * from ".COMMENT." where conid='$ar[cid]' and ctype='$ar[ctype]'";
	$db->query($sql);
	$re=$db->getRows();
	$rank_a=array(0,0,0,0);
	foreach($re as $v)
	{
		$i=$v['rank'];
		$rank_a[$i]++;
	}
	if(array_sum($rank_a)>0)
	{
		$tpl->assign("r1",ceil($rank_a[1]/array_sum($rank_a)*100));
		$tpl->assign("r2",ceil($rank_a[2]/array_sum($rank_a)*100));
		$tpl->assign("r3",ceil($rank_a[3]/array_sum($rank_a)*100));
	}
	$tpl->assign("rank_a",$rank_a);
	unset($_POST);
	//==================================================	
	$tpl->assign("config",$config);
	$tpl->assign("c_ar",$ar);
	
	$getstr=count($_GET)?'?'.implode('&',convert($_GET)):NULL;
	$return=basename($_SERVER['SCRIPT_FILENAME'])."$getstr";
	$tpl->assign("return","?forward=".urlencode($return));

	return $tpl->fetch($ar['temp'].'.htm');
}
?>