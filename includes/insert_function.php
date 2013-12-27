<?php
/**
 * Copyright :b2bbuilder
 * Powered by :b2bbuilder
 * Auther:brad
 * Date: 2008-11-04
 * Des:非公共免费代码，没有得到b2bbuilder许可，禁止传播，复制。
 */
//===================
include_once("arrcache_class.php");
$cache     = new ArrCache('cache/apidata/');
$cachetime = $config['cacheTime'];//数据调用缓存时间。
//===================
function insert_label($ar)
{
	global $tpl,$config;
	$type=$ar['type'];
	$arr=explode('_',$type);
	if(file_exists("$config[webroot]/includes/label/".$arr[0]."_label.php"))
		include_once("$config[webroot]/includes/label/".$arr[0]."_label.php");
	else
		include_once("module/".$arr[0]."/label.php");
	$con=$type($ar);
	$tpl->caching=false;
	if($tpl->statu)
		$tpl->template_dir=$tpl->statu;
	else
		$tpl->template_dir = $config['webroot']."/templates/".$config['temp']."/";
	return $con;
}
/**
 *二级城市域名调用．
 */
function insert_getSubCity($ar)
{	
	global $cache,$cachetime,$config;
	$limit=$ar['limit'];
	if(strlen($str=$cache->str_begin($ar,$cachetime))<=0)
	{
		if(!empty($config['baseurl']))
		{
			 global $db;
			 $sql="select * from ".DOMAIN." where isopen=1 and dtype=1 limit 0,$limit";
			 $db->query($sql);
			 $re=$db->getRows();
			 $str=NULL;
			 foreach($re as $v)
			 {
				$url='http://'.$v['domain'].'.'.$config['baseurl'];
				$str.='<a href="'.$url.'">';
				if($v['con2'])
					$str.=$v['con2'];
				else
					$str.=$v['con'];
				$str.='</a>&nbsp;';
			 }
		 }
	}
	$cache->str_end($str);
	return $str;
}
//公司调用
function insert_getUser($ar)
{
	global $cache,$cachetime,$dpid,$dcid;
	$ar[$dpid]=$dpid;
	$ar[$dcid]=$dcid;
	if(strlen($str=$cache->str_begin($ar,$cachetime))<=0)
	{	
		global $db,$config;
		
		$field=$ar['field'];
		$filter=$ar['filter'];
		$limit=$ar['limit'];
		$leng=$ar['leng'];
		$logo = $ar['logo'];
		$groupid=$ar['ugroupid'];
		$cid=$ar['catid'];

		$heig=$ar['height'];
		if (empty($heig))
             $heig=80;
		$width=$ar['width'];
		if (empty($width))
             $width=100;

		if(!empty($dpid))
			$sql=" and province='$dpid' ";
		if(!empty($dcid))
			$sql=" and city='$dcid' ";

		if(!empty($groupid))
			$sql=" and ifpay='$groupid' ";

		if(!empty($cid))
			$sql=" and catid like '%,$cid%'";
		if($logo)
			$sql.=" and logo!=''";
		if($filter=="new")
			$sql.=" order by uptime desc ";
		elseif($filter=="old")
			$sql.=" order by uptime asc ";
		elseif($filter=="rand")
			$sql.=" order by rand() ";
		elseif($filter=="rec")
			$sql.=" and statu=1 order by rank desc";
		elseif($filter=="star")
			$sql.=" and statu=2 order by rank desc";
		else
			$sql.=" order by uptime desc ";

		$sql="select userid,user,logo,$field from ".USER." where company!='' and grade>=1 $sql limit 0,".$limit;
		$db->query($sql);
		$fileds=explode(",",$field);
		while($k=$db->fetchRow())
		{
			$str.="<li>";
					if(!empty($logo))
					{
						if(empty($k['logo']))
							$str.="<a href='".geturl($k['userid'],$k['user'],$k['company'])."' title='' target='_blank'><img width=".$width." height=".$heig." src='$config[weburl]/image/default/nopic.gif'>";
						else
				     		$str.="<a href='".geturl($k['userid'],$k['user'],$k['company'])."' title='' target='_blank'><img width=\"".$width."\" height=\"".$heig."\" src='$k[logo]'>";
					}
					else
					{
						$str.="<a href='".geturl($k['userid'],$k['user'],$k['company'])."' target='_blank'>";
					}
				   foreach($fileds as $key=>$v)
				   {
					 	$str.=csubstr($k[$v],0,$leng)."&nbsp;";
				   }
			$str.="</a></li>";
		}
	}
	$cache->str_end($str);
	return $str;
}
//专题调用
function insert_getSpecial($ar)
{
	global $cache,$cachetime,$db,$config;
	if(strlen($str=$cache->str_begin($ar,$cachetime))<=0)
	{	
		$limit=isset($ar['limit'])?$ar['limit']:5;//条数
		$leng = $ar['leng'];//长度
		$sql="select * from ".SPE." where 1 order by `order` asc limit 0,$limit";//专题详情
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			$str.="<li>";
			$str.="<a href='$config[weburl]/?m=special&s=spd&name=$v[file_name]'>".csubstr($v['name'],0,$leng)."</a>";
			$str.="</li>";
		}
	}
	$cache->str_end($str);
	return $str;
}
//公告调用
function insert_getNotice($ar)
{
	global $cache,$cachetime;
	if(strlen($str=$cache->str_begin($ar,$cachetime))<=0)
	{	
		global $db,$config;
		$limit=$ar['limit'];	//条数
		$leng = $ar['leng'];	//标题长度
      
		if(empty($limit))
			$limit=5;
		if(empty($leng))
            $leng=30;
		
		$sql="SELECT * from ".ANNOUNCEMENT." where status>0 order by displayorder asc,id desc limit 0,".$limit;
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			if(!$v['url'])
			{
			      $str.='<li><a href="'.$config['weburl'].'/?m=announcement&s=detail&id='.$v['id'].'" target="_blank" title="'.$v['title'].'" >'.csubstr($v['title'],0,$leng).'</a></li>';
			}
			else
			{
			      $str.='<li><a href="'.$v['url'].'" target="_blank" title="'.$v['title'].'" >'.csubstr($v['title'],0,$leng).'</a></li>';
			}
		}
	}
	$cache->str_end($str);
	return $str;
}
?>