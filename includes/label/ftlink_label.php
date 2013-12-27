<?php
function ftlink($ar)
{
	global $config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$ar['temp']=empty($ar['temp'])?'ftlink_list':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	//#===========================================
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$sql="select * from ".WEBCONGROUP." where lang='$config[language]' order by sort asc";
		$db->query($sql);
		$groups = $db->getRows();
		//-------------------------------------
		$sql="select * from ".WEBCON." where lang='$config[language]' order by con_no asc";
		$db->query($sql);
		$all_web = $db->getRows();
		foreach($groups as $p)
		{
			foreach($all_web as $web)
			{
				if($web['con_group']==$p['id'])
				{
					$p['list'][]= $web;	
				}
			}
			$links[] = $p;
		}
		$tpl->assign("links",$links);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>