<?php
include_once("$config[webroot]/includes/page_utf_class.php");
//=============================================
if(empty($_GET['m'])||empty($_GET['s']))
	die('forbiden;');
$id=$_GET['id']*1;

//--------促销活动内容------------
$sql="select * from ".ACTIVITY."  where id='$id'  limit 1";
$db->query($sql);
$re['prom']=$db->fetchRow();

$re['prom']['ads_code']= str_replace('$smarty.get.id','',str_replace('$smarty.get.key',"",str_replace('$config.weburl',$config['weburl'],$re['prom']['ads_code'])));
$re['prom']['ads_code']=str_replace('}>','',str_replace('<{','',$re['prom']['ads_code']));

//--------促销商品内容------------
$sql="select a.id,a.pname,a.pic,a.price,a.market_price from ".PRO." a right join ".ACTIVITYPRODUCT." b on a.id=b.product_id where promotion_id='1' and activity_id='$id' and b.status=2 and a.statu>0 order by uptime desc ";
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$db->query($sql);

$info['info']=$db->getRows();
$info['page']=$page->prompt();	
$re['prolist']=$info;

$tpl->assign("pro",$re);
//=============================================
$tpl->assign("current","activity");
include_once("footer.php");

if(!empty($re['prom']['templates'])&&file_exists('module/activity/templates/'.$re['prom']['templates']))
	$out=tplfetch($re['prom']['templates']);
else
	$out=tplfetch('default_index.htm');
?>
