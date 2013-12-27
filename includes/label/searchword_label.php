<?php
function searchword($ar)
{
	global $config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$ar['temp']=empty($ar['temp'])?'notice_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		$sql="SELECT * FROM ".SWORD." ORDER BY nums DESC LIMIT $limit";
		$db->query($sql);
		$sword=$db->getRows();
		//==================================================	
		$tpl->assign("config",$config);
		$tpl->assign("sword",$sword);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>