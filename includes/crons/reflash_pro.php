<?php
if(isset($admin_read_config))
{
	$cron_config['name']=array('cn'=>'刷新商城里的产品时间','en'=>'刷新商城里的产品时间');
	$cron_config['des']=array('cn'=>'用于刷新商城里用户发布的产品时间，此项需要结合运营来决定是否启用，比如为高级会员启用此功能，高级用户便不需要每天过来刷新产品','en'=>'');
	$cron_config['week']='-1';//设置周几执行，此设置将覆盖下面的“日”选项。 -1,不作限制 Sunday,每周日 Monday,每周一 ....
	$cron_config['day']='-1';//设置任务哪天执行，默认为每天。  -1，不作限制 01每月一号
	$cron_config['hours']='00';//设置任务哪个小时执行 01,02....24
	$cron_config['minutes']='00';//设置任务分钟执行 01,02.....59
}
else
{
	$unit_time=24; //unit_time为刷新与现在距离时间之前的所有信息，单位为小时；
	$info_type=2;  //info_type为要刷新的类型;1为产品，2为产品，
	$group=NULL;   //ifpay为刷新的会员组
	//-----------------------------------------
	$d=$unit_time*3600;
	$nt=date("Y-m-d H:m:s");
	if(!empty($group))
	{
		$sqlu="select userid from ".USER." where ifpay='".$group."'";
		$db->query($sqlu);
		$de=$db->getRows();
		if(is_array($de["userid"]))
			$usid=explode(",",$de["userid"]);
		else
			$usid=0;	
		$sql="update ".PRO." set uptime='$nt' where UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(uptime)>=$d and userid in($usid)";
		$db->query($sql);
	}
	else
	{		
		$sql="update ".PRO." set uptime='$nt' where UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(uptime)>=$d";
		$db->query($sql);
	}                
}
?>