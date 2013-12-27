<?php
if(isset($admin_read_config))
{
	$cron_config['name']=array('cn'=>'自动阻止非法用户','en'=>'');
	$cron_config['des']=array('cn'=>'此任务依赖于统计任务，想使用此任务需启用统计功能','en'=>'');
	$cron_config['week']='-1';//设置周几执行，此设置将覆盖下面的“日”选项。 -1,不作限制 Sunday,每周日 Monday,每周一 ....
	$cron_config['day']='-1';//设置任务哪天执行，默认为每天。  -1，不作限制 01每月一号
	$cron_config['hours']='00';//设置任务哪个小时执行 01,02....24
	$cron_config['minutes']='00';//设置任务分钟执行 01,02.....59
}
else
{
	function getipdata()
	{
		global $db,$config;
		$sre=array();
		$sql="select ip,type from ".IPLOCK." where statu=1";
		$db->query($sql);
		$re=$db->getRows();
		foreach ($re as $v)
		{
			if($v['type']==1)
				$stop_view[]=$v['ip'];
			else
				$stop_reg[]=$v['ip'];
		}
		$stop_view=serialize($stop_view);
		$stop_reg=serialize($stop_reg);
		
		$write_str='<?php $stop_view = unserialize(\''.$stop_view.'\');$stop_reg=unserialize(\''.$stop_reg.'\');?>';//生成要写的内容
		$fp=fopen($config['webroot'].'/config/stop_ip.php','w');
		fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
		fclose($fp);
	}
	
	$ips=array('61.164.43.236','127.0.0.1');//不再控制的系统之中
	$sql="select ip,username,count(*) as num from ".PV." group by ip order by num desc limit 0,1000";
	$db->query($sql);
	$ipNum=$db->num_rows();
	$list=$db->getRows();
	foreach($list as $v)
	{
		if($v['num']>=400&&!in_array($v['ip'],$ips))
		{
			if(empty($v['username']))
			{
				$locktime=time()+3600*24*365;$otime=time();
				
				$sql="select ip from ".IPLOCK." where ip='$v[ip]'";
				$db->query($sql);
				if($db->num_rows()<=0)
				{
					$sql="insert into ".IPLOCK." 
					(ip,reason,optime,stoptime,autorelease,statu,type)
					VALUES
					('$v[ip]','auto','$otime','$locktime','1','1','1')"; 
					$db->query($sql);
				}
				
			}
			else
			{
				$sql="update ".ALLUSER." set statu='-2' where user='$v[username]' ";
				$db->query($sql);
			}
		}
	}
	//getipdata();
}
?>