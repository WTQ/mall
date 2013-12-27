<?php
function brand($ar)
{
	global $config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'brand_default':$ar['temp'];
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		$img=$ar['pic'];
		$ids=$ar['ids'];
		$catid=$ar['catid'];
		$pid=$ar['pid'];
		
		if(is_numeric($rec))
			$scl=" and status ='$rec'";
		else
			$scl=" and status >0";
		if(!empty($img))
			$scl.=" and logo !=''";
		if(!empty($ids))
			$scl=" id in ($ids)";
		if(!empty($catid))	
			$scl.=" and catid = $catid ";
		if(!empty($pid))
		{
			$sql="select id from ".BRANDCAT." where parent_id=$pid";
			$db->query($sql);
			$de=$db->getRows();
			foreach($de as $key=>$val)
			{
				$str.=$val['id'].',';
			}
			$str=$str.$pid;
			$scl.=" and catid in ($str) ";
		}
		$sql="SELECT * FROM ".BRAND." WHERE 1 $scl ORDER BY displayorder asc LIMIT 0,$limit";
		$db->query($sql);
		$re=$db->getRows();
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("brand",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>