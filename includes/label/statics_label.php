<?php
function statics($ar)
{
	//新闻是1，产品是2，产品是3，展会是4,5是招聘，6是企业视频,7招商，
	global $config,$db,$tpl;
	$ar['temp']=empty($ar['temp'])?'notice_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	
	
	$ctype=$ar['ctype'];
	$id=$ar['id'];
	//================评论数===========
	$sql="select count(*) as num from ".COMMENT." where ctype='$ctype' and conid='$id'";
	$db->query($sql);
	$res=$db->fetchRow();
	$re['revs']=empty($res['num'])?0:$res['num'];
	//=================================================
	$re['ctype']=$ctype;
	$re['id']=$id;
	$tpl->assign("config",$config);
	$tpl->assign("statics",$re);

	return $tpl->fetch($ar['temp'].'.htm',$flag);
}
?>