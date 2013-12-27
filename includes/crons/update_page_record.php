<?php
if(isset($admin_read_config))
{
	$cron_config['name']=array('cn'=>'统计','en'=>'statistics');
	$cron_config['des']=array('cn'=>'网站统计任务，要启用此任务，需要在系统设置中先启用统计功能','en'=>'');
	$cron_config['week']='-1';//设置周几执行，此设置将覆盖下面的“日”选项。 -1,不作限制 Sunday,每周日 Monday,每周一 ....
	$cron_config['day']='-1';//设置任务哪天执行，默认为每天。  -1，不作限制 01每月一号
	$cron_config['hours']='00';//设置任务哪个小时执行 01,02....24
	$cron_config['minutes']='00';//设置任务分钟执行 01,02.....59
}
else
{
	//计功能，统计前一天的记录存入记录总表，并清空前一天的的详细记录表，该函数在每天的第一浏览的时候执
	//===================================pv总数
	$sql="select count(*) as num from ".PV;
	$db->query($sql);
	$rs=$db->fetchRow();
	$pvs=$rs['num']*1;
	//====================================独立ip总数
	$sql="select count(distinct ip) as ips from ".PV;
	$db->query($sql);
	$rs=$db->fetchRow();
	$ips=$rs['ips']*1;
	//====================================url总数
	$sql="select count(url) as urls from ".PV;
	$db->query($sql);
	$rs=$db->fetchRow();
	$urls=$rs['urls']*1;
	//====================================最受欢迎的url
	$sql="select  url,count(*) as num from ".PV." group by url order by num desc limit 1";
	$db->query($sql);
	$rs=$db->fetchRow();
	$mostpopurl=$rs['url']*1;
	//====================================上线会员数
	$sql="select count(distinct username) as users from ".PV;
	$db->query($sql);
	$rs=$db->fetchRow();
	$onusers=$rs['users']*1;
	//=====================================前一天所有注册的新的会员
	$sql="select count(*) as reguser from ".ALLUSER." where TO_DAYS(NOW())-TO_DAYS(regtime)<=1";
	$db->query($sql);
	$rs=$db->fetchRow();
	$nregusers=$rs['reguser']*1;
	//======================================前一天发布产品数
	$sql="select count(*) as pros from ".PRO." where TO_DAYS(NOW())-TO_DAYS(uptime)<=1";
	$db->query($sql);
	$rs=$db->fetchRow();
	$prods=$rs['pros']*1;
	//=========================================前一天发布展会数
	$sql="select count(*) as exhibs from ".EXHIBIT." where TO_DAYS(NOW())-TO_DAYS(addTime)<=1";
	$db->query($sql);
	$rs=$db->fetchRow();
	$exhs=$rs['exhibs']*1;
	//========================================前一天发布资讯数
	$sql="select count(*) as newss from ".NEWSD." where TO_DAYS(NOW())-TO_DAYS(uptime)<=1";
	$db->query($sql);
	$rs=$db->fetchRow();
	$newss=$rs['newss']*1;
	//==========================================以下是写入记录总表
	$ntime=date("Y-m-d H:i:s");
	$sql=$sql="insert into ".PAGEREC."
	(totalurl,mostpopularurl,pageviews,totalip,visitusernum,reguser,pronum,newsnum,time)
	values
	('$urls','$mostpopurl','$pvs','$ips','$onusers','$nregusers','$prods','$newss','$ntime')";
	$db->query($sql);
	//==========================================以下是清空详细记录表的记录
	$sql="delete from ".PV;
	$db->query($sql);
}
?>