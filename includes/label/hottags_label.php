<?php
function hottags($ar)
{
	global $db,$config,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'hottags_default':$ar['temp'];
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		$limit=$ar['limit'];
		$ctype=$ar['ctype'];
		$sql="select distinct(a.tagname) from ".TAGS." a, ".CTAGS." b where a.tagname=b.tagname and b.type='$ctype' 
		 order by a.total desc limit 0, $limit";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $key=>$v)
		{
			if($ctype==1)
				$a='?m=product&s=list&';
			if($ctype==2)
				$a='?m=offer&s=offer_list&';
			if($ctype==3)
				$a='?m=news&s=news_list&';
			if($ctype==4)
				$a='?m=exhibition&s=exhibition_list&';
			$re[$key]['link']="$config[weburl]/$a?key=".urlencode($v['tagname']);
		}
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("hottags",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);

}
?>