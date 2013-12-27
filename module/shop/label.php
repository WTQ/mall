<?php
function user($ar)
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
		if($dpid)
			$scl=" and province='$dpid' ";
		if($dcid)
			$scl=" and city='$dcid' ";
		if($rec)
			$scl.=" and statu='$rec'";
			
		$sql="SELECT * from ".SHOP." WHERE company!='' $scl ORDER BY uptime DESC LIMIT $spointer,$limit";
		$db->query($sql);
		$user=$db->getRows();
		foreach($user as $key=>$v)
		{
			if($v['catid'])
			{
				unset($cat);
				$sql="select name from ".SHOPCAT." where id = '$v[catid]' ";
				$db->query($sql);
				while($k=$db->fetchRow())
					$cat[]=$k['name'];
				if(!empty($cat))
					$user[$key]['name']=implode(",",$cat);
			}
		}
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("user",$user);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>