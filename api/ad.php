<?php
/**
 * JS的广告调用
 * powered by b2bbuilder
 * Auther:brad
 * date:2008-12-18
 */
header("Content-Encoding:none");
include_once("../includes/arrcache_class.php");
include_once("../includes/global.php");
//=====================
$cache = new ArrCache('../cache/ad/');
$config["weburl"]=str_replace('api','',$config["weburl"]);
$spid = isset($_COOKIE["SPID"])?$_COOKIE["SPID"]:NULL;
$scid = isset($_COOKIE["SCID"])?$_COOKIE["SCID"]:NULL;
$id   = $_GET['id']*1;
$_GET['catid']*=1;

if(!$cache->begin(array($id,$spid,$scid,$_GET['catid']),3600*0))
{
	$sub_sql=$tsql=NULL;$nt=time();
	if(!empty($dpid))
		$sub_sql=" and province='$dpid' and city=''";
	if(!empty($dcid))
		$sub_sql=" and city='$dcid' ";
	if(empty($dcid)&&empty($dpid))
		$sub_sql=" and (province='' or province is NULL) and (city='' or city is NULL) ";
	if(!empty($_GET['catid']))
		$tsql.=" and catid='$_GET[catid]'";
	if(!empty($_GET['name']))
		$tsql.=" and name='$_GET[name]'";
		
	$db->query("update ".ADVSCON." set shownum=shownum+1 where group_id='$id'");//广告展示次数加
	$db->query("select ad_type,width,height,id from ".ADVS." where ID='$id'");
	$ad_re=$db->fetchRow();
	if(empty($ad_re['id']))
	{
		$sql="insert into ".ADVS." (id,ad_type,width,height,onurl) values ('$id','1','100','100','$_GET[onurl]')";
		$db->query($sql);
	}
	if($ad_re['ad_type']==2)
	{//flash幻灯片
		$sql="select * from ".ADVSCON." where group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' and type=3 $sub_sql $tsql order by sort_num asc ";
		$db->query($sql);
		$re=$db->getRows();
		if(count($re)<=0)
		{
			$sql="select * from ".ADVSCON." where 
			group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql and (catid='' or catid is NULL) order by sort_num asc";
			$db->query($sql);
			$re=$db->getRows();
		}
		echo $str=ad_flash($re,$ad_re);
	}
	elseif($ad_re['ad_type']==3)
	{//对联广告
		$sql="select * from ".ADVSCON." where group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql $tsql order by sort_num asc";
		$db->query($sql);
		$re=$db->getRows();
		if(count($re)<=0)
		{
			$sql="select * from ".ADVSCON." where 
			group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql and (catid='' or catid is NULL) order by sort_num asc";
			$db->query($sql);
			$re=$db->getRows();
		}
		echo duilian_ad($re,$ad_re);
	}
	else
	{//普通广告
		$sql="select * from ".ADVSCON." where group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql $tsql order by sort_num asc";
		$db->query($sql);
		$re=$db->getRows();
		if(count($re)<=0)
		{
			$sql="select * from ".ADVSCON." where 
			group_id='$id' and isopen=1 and etime>='$nt' and stime<='$nt' $sub_sql and (catid='' or catid is NULL) order by sort_num asc limit 0,1";
			$db->query($sql);
			$re=$db->getRows();
		}
		foreach($re as $v)
		{
			echo get_add($v);
		}	
	}
}
$cache->end();
//============================================================
function get_add($re)
{
		global $config;
		$str=NULL;
		if($re["type"]==1)
		{
			$re['con']=nl2br($re['con']);
			$re['con']=str_replace("\t",'',$re['con']);
			$re['con']=str_replace("\r\n",'',$re['con']);
			$re['con']=str_replace("\r",'',$re['con']);
			$re['con']=str_replace("\n",'',$re['con']);
			if($re["url"])
				$str="<a href=".$re["url"]." target=_blank>".$re["con"]."</a>";
			else
				$str=$re["con"];
			return "document.write('".$str."');";
		}
		elseif($re["type"]==2)
		{
			$str=$re["con"];$jstr=NULL;
			$str=str_replace("'","\"",$str);
			$ar=explode("\n",$str);
			for($i=0;$i<count($ar);$i++)
			{
				$jstr.="document.writeln('".trim($ar[$i])."');\n";
			}
			return $jstr;
		}
		elseif($re["type"]==3)
		{
			if($re["url"])
				$str="<a href=".$re['url']." target=_blank><img src=".$config["weburl"]."/uploadfile/ads/".$re['picName']." border=0></a>";
			else
				$str="<img src=".$config["weburl"]."/uploadfile/ads/".$re['picName']." >";
			return "document.write('".$str."');";
		}
		elseif($re["type"]==4)
		{
			$str='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="'.$re["width"].'" height="'.$re["height"].'">'.
			'<param name="movie" value="'.$config["weburl"]."/uploadfile/ads/".$re["picName"].'" />'.
			'<param name="quality" value="high" />'.
			'<embed width="'.$re["width"].'" height="'.$re["height"].'" src="'.$config["weburl"]."/uploadfile/ads/".$re['picName'].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>'.
			'</object>';
			return "document.write('".$str."');";
		}
}
function duilian_ad($re,$ar)
{
	if(empty($re))
		return NULL;
	$str="
	lastScrollY=0;
	function heartBeat()
	{ 
		var diffY;
		if (document.documentElement && document.documentElement.scrollTop)
			diffY = document.documentElement.scrollTop;
		else if (document.body)
			diffY = document.body.scrollTop

		percent=.1*(diffY-lastScrollY); 
		if(percent>0)
			percent=Math.ceil(percent); 
		else
			percent=Math.floor(percent); 
		document.getElementById('leftad1').style.top=parseInt(document.getElementById('leftad1').style.top)+percent+'px';
		document.getElementById('rightad1').style.top=parseInt(document.getElementById('leftad1').style.top)+percent+'px';
		lastScrollY=lastScrollY+percent; 
	}
	document.write('<style type=\'text/css\'>#leftad1,#rightad1{width:".$ar['width']."px;height:".$ar['height']."px;overflow:hidden;}</style>');
	document.write('<DIV id=leftad1 style=\'left:2px;POSITION:absolute;TOP:120px;\'>');".get_add($re[0])."document.write('</div><DIV id=rightad1 style=\'right:2px;POSITION:absolute;TOP:120px;\'>');".get_add($re[1])."document.write('</div>');
	heartBeat();
	";
	return $str;
}
function ad_flash($re,$ar)
{
	global $db,$config;
	$width  = $ar['width']; //宽度
	$height = $ar['height'];//高度
	if(!empty($re))
	{
		foreach($re as $v)
		{
			$ssp[]=$config['weburl'].'/uploadfile/ads/'.$v['picName'];
			$ssl[]=$v['url'];
		}
	}
	else
	{
		$ssp[]='';
		$ssl[]='';
	}
   $str="
	var swf_width=$width;
	var swf_height=$height;
	var files='".implode("|",$ssp)."';
	var links='".implode("|",$ssl)."';
	var texts='';
	document.write('<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\">');
	document.write('<param name=\"movie\" value=\"$config[weburl]/image/default/hot_new.swf\"><param name=\"quality\" value=\"high\">');
	document.write('<param name=\"menu\" value=\"false\"><param name=wmode value=\"opaque\">');
	document.write('<param name=\"FlashVars\" value=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'\">');
	document.write('<embed src=\"$config[weburl]/image/default/hot_new.swf\" wmode=\"opaque\" FlashVars=\"bcastr_file='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'& menu=\"false\" quality=\"high\" width=\"'+ swf_width +'\" height=\"'+ swf_height +'\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />'); document.write('</object>'); 
		";

   return $str;
}

?>
