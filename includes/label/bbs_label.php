<?php
function bbs($ar)
{
	global $config,$tpl;
	useCahe();
	$flag=md5(implode(",",$ar));
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	$ar['temp']=empty($ar['temp'])?'bbs_default':$ar['temp'];
	if(!$tpl->is_cached($ar['temp'].".htm",$flag))
	{	
		//$db=new dba($ar['dbhost'],$ar['dbuser'],$ar['dbpass'],$ar['dbname']);
		$limit=$ar['limit'];
		$img=$ar['pic'];
		$fid=$ar['fid'];
		if(!empty($img))
			$scl.=" and a.tid=b.tid and b.isimage='1'";
		if(!empty($fid))
			$scl.=" and fid ='$fid'";
		$sql="SELECT a.tid,a.subject,b.attachment FROM cdb_threads a,cdb_attachments b WHERE 1 $scl and a.replies='0' order by a.replies desc,a.lastpost desc LIMIT 0,$limit";
		$db->query($sql);
		$re=$db->getRows();
		//==================================================
		$tpl->assign("config",$config);
		$tpl->assign("bbs",$re);
	}
	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>