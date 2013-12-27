<?php
/**
 * Powered by B2Bbuilder
 * Copyright http;//www.b2b-builder.com
 */

function execute_transact($transact_id = 0)
{
	global $db, $config, $cron_config, $systime;
	$sql = "select * from ".CRON." where ".($transact_id>0 ? "id='$transact_id'" : " active=1 and	nexttransact<='$systime'")." order by nexttransact limit 1";
	$db->query($sql);
	$cron_transact = $db->fetchRow();
	if($cron_transact['script'])
	{
		$locked = $config["webroot"]."/cache/cron_sign_".$cron_transact['id'].".lock";
		if(is_writable($locked) && filemtime($locked) > $systime-600)
		{
			return false;
		}
		else
		{
			touch($locked);
		}
		$script = $config["webroot"]."/includes/crons/".$cron_transact['script'];
		@set_time_limit(0);
		ignore_user_abort(TRUE);
		if(file_exists($script))
		{
			include($script);
			update_transact($cron_transact);
		}
		unlink($locked);
	}

	$sql = "select nexttransact from ".CRON." where  active=1 order by nexttransact limit 1";
	$db->query($sql);
	$re = $db->fetchRow();
	if($re['nexttransact'])
	{
		$cron['nexttransact'] = $re['nexttransact'];
		$write_config_con_str = serialize($cron);
		$write_config_con_str = '<?php $cron_config = unserialize(\''.$write_config_con_str.'\');?>';
		$fp = fopen($config["webroot"].'/config/cron_config.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));
		fclose($fp);
		$cron_config["nexttransact"] = $re['nexttransact'];
	}
}

function update_transact($cron_transact)
{
	global $db, $config;
	$systime=time();
	$week = $cron_transact['week'];
	$day = $cron_transact['day'];
	$hours = $cron_transact['hours'];
	$minutes = $cron_transact['minutes'];
	$nexttransact = 0;
	if($week != '-1')
	{
		$nexttransact = strtotime("next ".$week);
		$nexttransact += (intval($hours)*60*60);
		$nexttransact += (intval($minutes)*60);
	}
	else if($day == '-1')
	{
		$time_str = gmdate('Y-m-d', strtotime("+1 day")+8*3600);
		$time_str .= " $hours:$minutes:00";
		$nexttransact = strtotime($time_str);
	}
	else
	{
		$time_str = gmdate('Y-m', strtotime("next Month")+8*3600);
		$time_str .= "-";
		$time_str .= $day;
		$time_str .= " $hours:$minutes:00";
		$nexttransact = strtotime($time_str);
	}
	$sql = "update ".CRON." set lasttransact='$systime', nexttransact='$nexttransact' where id='$cron_transact[id]'";
	$db->query($sql);
}
?>