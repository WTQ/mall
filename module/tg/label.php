<?php
function tg($ar)
{
	global $cache,$cachetime,$dpid,$dcid,$config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$ar['temp']=empty($ar['temp'])?'user_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		$spointer=empty($ar['start_pointer'])?0:$ar['start_pointer'];
		if($rec)
			$scl.=" where status='$rec'";
		$spointer=empty($ar['start_pointer'])?0:$ar['start_pointer'];	
		
		$sql="SELECT * from ".TG." $scl ORDER BY displayorder LIMIT $spointer,$limit";
		$db->query($sql);
		$tg=$db->getRows();
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("tg",$tg);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);

}
?>