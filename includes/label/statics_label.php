<?php
function statics($ar)
{
	//������1����Ʒ��2����Ʒ��3��չ����4,5����Ƹ��6����ҵ��Ƶ,7���̣�
	global $config,$db,$tpl;
	$ar['temp']=empty($ar['temp'])?'notice_default':$ar['temp'];
	$tmpdir=$config['webroot']."/templates/".$config['temp']."/label/".$ar['temp'].".htm";
	if(file_exists($tmpdir))
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/label/";
	else
		$tpl->template_dir = $config['webroot']."/templates/default/label/";
	
	
	$ctype=$ar['ctype'];
	$id=$ar['id'];
	//================������===========
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