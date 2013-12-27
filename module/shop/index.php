<?php
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
//==========================================================
	if(!empty($_GET['province']))
		$sqls.=" and a.provinceid='$_GET[province]'";
	if(!empty($_GET['city']))
		$sqls.=" and a.cityid='$_GET[city]'";
	if(!empty($_GET['id']) and is_numeric($_GET['id']))
	{
		$str[]=$_GET['id']*1;
		$sql="select id from ".SHOPCAT."  where parent_id='$_GET[id]' ";
		$db->query($sql);
		$ss=$db->getRows();
		if($ss)
		{
			foreach($ss as $val)
			{
				$str[]="$val[id]";	
			}
		}
		$ss=implode(',',$str);
		$sqls.=" and a.catid in ($ss)";
	}
	if(!empty($_GET['keys']))
		$sqls.=" and (a.company regexp '".trim($_GET['keys'])."')";
	
	$sql="select a.company,a.userid,a.user,a.tel,a.area,a.logo,a.addr,b.sellerpoints,b.name from ".USER." a left join  ".ALLUSER." b on a.userid=b.userid WHERE a.shop_statu=1 $sqls "; 
			
	include_once("includes/page_utf_class.php");
	$page = new Page;
	$page->url=$config['weburl'].'/';
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$db->query($sql);
	$re["page"]=$page->prompt();
	/**************************************/
	$re["list"]=$db->getRows();
	foreach($re["list"] as $key=>$v)
	{
		$sql="select id,pname,userid,market_price,price,pic from ".PRO." where userid='".$re["list"][$key]['userid']."' and statu>0 limit 0,4";
		$db->query($sql);
		$re["list"][$key]['pro']=$db->getRows();
		
			
		$sql="select * from ".POINTS." order by id";
		$db->query($sql);
		$de=$db->getRows();
		foreach($de as $k=>$v)
		{
			$ar=explode('|',$v['points']);
			if($re["list"][$key]['sellerpoints']<=$ar[1] and $re["list"][$key]['sellerpoints']>=$ar[0])
			{
				$re["list"][$key]["sellerpointsimg"]=$v['img'];
			}
		}
			
		$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id where b.userid='".$re["list"][$key]['userid']."' and a.userid <> '".$re["list"][$key]['userid']."'";
		$db->query($sql);
		$count=$db->fetchField('count');
		if($count!=0)
		{
			$sql="select count(*) as count  from ".PCOMMENT." a left join ".PRO." b on a.pid=b.id where b.userid='".$re["list"][$key]['userid']."' and a.userid <> '".$re["list"][$key]['userid']."' and a.goodbad=1";
			$db->query($sql);
			$re["list"][$key]["favorablerate"]=($db->fetchField('count')/$count)*100;
		}else{
			$re["list"][$key]["favorablerate"]="0";
		}
		$sql="select count(*) as count  from ".PRO." where userid='".$re["list"][$key]['userid']."' ";
		$db->query($sql);
		$re["list"][$key]['count']=$db->fetchField('count');
	}
	
	$tpl->assign("info",$re);
	$tpl->assign("province",GetDistrict());
//========================================================================
	$sql="select id,name from ".SHOPCAT."  where parent_id=0 order by displayorder ,id";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("cat",$re);
	
$tpl->assign("current","shop");
include_once("footer.php"); 
$out=tplfetch("companys_index.htm",$flag);
?>