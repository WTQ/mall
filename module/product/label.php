<?php
function product($ar)
{
	global $cache,$cachetime,$dpid,$dcid,$daid,$config,$db,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'pro_default':$ar['temp'];
	
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{
		$limit=$ar['limit'];
		$rec=$ar['rec'];
		$comp=$ar['comid'];
		$catid=$ar['catid'];
		$ptype=$ar['ptype'];
		$proid=$ar['proid'];
		
		if($dpid)
			$scl=" and b.provinceid='$dpid' ";
		if($dcid)
			$scl=" and b.cityid='$dcid' ";
		if($daid)
			$scl=" and b.areaid='$daid' ";
			
		if(is_numeric($rec))
			$scl.=" and a.statu='$rec'";
		else
			$scl.=" and a.statu>0";
		if(!empty($comp))
			$scl.=" and a.userid=$comp ";
		if(!empty($catid))
			$scl.=" and LOCATE($catid,a.catid)=1";
		if(!empty($ptype))
			$scl.=" and a.ptype=$ptype ";
		if(!empty($proid))
			$scl.=" and a.id in($proid)";
			
		$sql="SELECT a.id,a.pname,a.userid,a.pic,a.price,a.uptime,b.user,b.company FROM ".PRO." a left join ".USER." b 
		on a.userid=b.userid WHERE 1 $scl ORDER BY uptime DESC LIMIT 0,$limit";
		$db->query($sql);
		$re=$db->getRows();
		
		foreach($re as $k)
		{	
			$k['img']=$config['weburl']."/image/default/nopic.gif";
			if( $k['pic']!='' )
			{
				$pic = explode( ',',$k['pic'] );
				foreach( $pic as $v ){
					if(file_exists($config['webroot']."/$v")){
						$k['img']=$config['weburl']."/".substr($v,0,strrpos($v,'/')+1).'big_'.substr($v,strrpos($v,'/')+1);
						break;
					}
				}
			}
			$pro[]=$k;
		}
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("pro",$pro);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>