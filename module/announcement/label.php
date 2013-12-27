<?php
function notice($ar)
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
		
		$sql="SELECT * from ".ANNOUNCEMENT." where status>0  order by displayorder limit 0,".$limit;
		$db->query($sql);
		$notice=$db->getRows();
		//==================================================	
		$tpl->assign("config",$config);
		$tpl->assign("notice",$notice);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>