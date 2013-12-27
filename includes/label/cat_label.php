<?php
function cat($ar)
{
	global $config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'cat_default':$ar['temp'];
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		if($ar['rec'])
			$ssql.=" and isindex=$ar[rec]";

		$ctype=$ar['ctype'];
		
		$ctype=PCAT;
		$link='?m=product&s=list';	
		
		$re=array();
		$sql="select * from $ctype $sql WHERE catid<9999 $ssql order by nums asc,char_index asc";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key=>$v)
		{
			$s=$v['catid']."00";
			$b=$v['catid']."99";
			$sql="select * from $ctype 
			where  catid>$s and catid<$b $ssql order by nums asc,char_index asc";
			$db->query($sql);
			$sre=$db->getRows();
			foreach($sre as $skey=>$sv)
			{
				$s=$sv["catid"]."00";
				$b=$sv["catid"]."99";
				$sql="select * from $ctype 
					where catid>$s and catid<$b $ssql order by nums asc,char_index asc";
				$db->query($sql);
				$sre[$skey]["scat"]=$db->getRows();
			}
			$re[$key]["scat"]=$sre;
		}	
		$tpl->assign("config",$config);
		$tpl->assign("cat",$re);
		$tpl->assign("link",$link);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>