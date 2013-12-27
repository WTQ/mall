<?php
function vote($ar)
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
		$id=$ar['id'];
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		if($rec)
		{
			$str.=" and  tj=1";	
		}
		$sql="select * from ".NEWSVOTE." where 1 limit 0,$limit";
		$db->query($sql);
		$vote=$db->getRows();
		foreach($vote as $key=>$val)
		{
			$vid.=$val['id'].',';
			if(strtotime($val['time'])-time()<0 and strtotime($val['time']))
			{
				$vote[$key]['end']='1';	
			}
			if($_COOKIE['vote'.$buid.$val['id']])
			{
				$vote[$key]['ip']='1';	
			}
			$str=explode('|',$val['votetext']);
			for($i=0;$i<count($str);$i++)
			{
				$vote[$key]['item'][$i]=explode(',',$str[$i]);
			}
		}
		//==================================================	
		$tpl->assign("config",$config);
		$tpl->assign("vote",$vote);
		$tpl->assign("vid",substr($vid,0,-1));
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>