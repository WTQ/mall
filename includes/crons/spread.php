<?php
if(isset($admin_read_config))
{
	$cron_config['name']=array('cn'=>'定时清空用户推广信息','en'=>'');
	$cron_config['des']=array('cn'=>'对用户外发连接吸引别人注册后可以获得积分，此项任务用于对统计过程中的些垃圾信息清理','en'=>'');
	$cron_config['week']='-1';//设置周几执行，此设置将覆盖下面的“日”选项。 -1,不作限制 Sunday,每周日 Monday,每周一 ....
	$cron_config['day']='-1';//设置任务哪天执行，默认为每天。  -1，不作限制 01每月一号
	$cron_config['hours']='00';//设置任务哪个小时执行 01,02....24
	$cron_config['minutes']='00';//设置任务分钟执行 01,02.....59
}
else
{
	$nt=time()-2592000;
	$sql="delete from ".SPREAD." where ctime<$nt";
	$db->query($sql);
}
?>