<?php
/**
 * Email:zyfang1115@163.com
 * Auther:Brad
 * 2008,7,17
 */
//============================
include_once("includes/global.php");
include_once("includes/smarty_config.php");
useCahe();
$flag=$config["temp"];
if(!$tpl->is_cached("sub_domain_city.htm"))
{	
	$sql="select domain,con from ".DOMAIN." where isopen=1 and dtype=1 group by con";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$v)
	{
		$sql="select domain from ".DOMAIN." where con='$v[con]' and (con2 is NULL or con2='') and (con3 is NULL or con3='')";
		$db->query($sql);
		$re[$key]['domain']=$db->fetchField('domain');
		
		$sql="select domain,con2 from ".DOMAIN." where isopen=1 and dtype=1 and con='$v[con]' and con2!='' group by con2";
		$db->query($sql);
		$city=$db->getRows();
		foreach($city as $k=>$val)
		{
			$sql="select domain from ".DOMAIN." where con='$v[con]' and con2='$val[con2]' and (con3 is NULL or con3='')";
			$db->query($sql);
			$city[$k]['domain']=$db->fetchField('domain');
			
			
			$sql="select domain,con3 from ".DOMAIN." where isopen=1 and dtype=1 and con='$v[con]' and con2='$val[con2]' and con3!=''";
			$db->query($sql);
			$area=$db->getRows();
			$city[$k]['area']=$area;
		}
		$re[$key]['city']=$city;
	}
	$tpl->assign("prov",$re);
	include_once("footer.php");
}
$tpl->display("sub_domain_city.htm",$flag);
?>
