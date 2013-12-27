<?php
$id=!empty($_GET["id"])?$_GET["id"]*1:NULL;
$key=!empty($_GET["key"])?trim($_GET["key"]):NULL;
$firstRow=!empty($_GET["firstRow"])?$_GET["firstRow"]:NULL;
$totalRows=!empty($_GET["totalRows"])?$_GET["totalRows"]:NULL;
$sprice=!empty($_GET["sprice"])?$_GET["sprice"]*1:NULL;
$eprice=!empty($_GET["eprice"])?$_GET["eprice"]*1:NULL;
$province=!empty($_GET["province"])?$_GET['province']:NULL;
$orderby=!empty($_GET["orderby"])?$_GET['orderby']*1:NULL;
//===================================分类
if(is_numeric($id))
{
	$cat=readCat($id);
	if(isset($cat["pcat"]["catid"]))
		$pcat="<a href='$config[weburl]/?m=product&s=list&id=".$cat["pcat"]["catid"]."'>".$cat["pcat"]["cat"]."</a> &raquo; ";
	$guide=$pcat.$cat["cat"];
	$tpl->assign("guide",$guide);
	//-----------------------------分类关连的品牌
	if(!empty($cat['brand']))
	{
		$sql="select * from ".BRAND." where id in ( $cat[brand] ) order by displayorder asc ";
		$db->query($sql);
		$re=$db->getRows();
		$tpl->assign("brand",$re);
	}
	//-----------------------------获取分类下自定义字段搜索项
	$sql="select display_type,field,field_name,item from ".PROPERTYVALUE." where is_search=1 and display_type in (3,4,5) and property_id='$cat[ext_field_cat]'";
	$db->query($sql);
	$catfile=$db->getRows();
	foreach($catfile as $fkey=>$v)
	{
		$catField=explode(',',$v['item']);
		foreach($catField as $skey=>$sv)
		{
			$catField[$skey]=explode('|',$sv);
		}
		$catfile[$fkey]['catItem']=$catField;
		//------组合皖搜索
		if(!empty($_GET[$v['fieldName']]))
			$ext_sql.=' and b.'.$v['field'].'=\''.$_GET[$v['fieldName']].'\'';
	}
	$tpl->assign("catfile",$catfile);
	//---------------------------------按分类搜索
	$scl.=" and LOCATE(".intval(trim($_GET['id'])).",a.catid)=1 ";//按类别搜索
}

if(!empty($key))
	$scl.=" and ( a.keywords like '%$key%' or a.pname like '%$key%' )";
if(!empty($province))
	$scl.=" and a.province='$province' ";
if(!empty($_GET['brand']))
	$scl.=" and a.brand='".$_GET['brand']."' ";

if($sprice)
	$scl.=" and a.price>='$sprice' ";
if($eprice)
	$scl.=" and a.price<='$eprice' ";

if($orderby==2)
	$scl.=" order by a.goodbad desc";	
elseif($orderby==3)
	$scl.=" order by a.uptime asc";
elseif($orderby==4)
	$scl.=" order by a.price desc";
elseif($orderby==5)
	$scl.=" order by a.price asc";
elseif($orderby==6)
	$scl.=" order by a.sell_amount asc";
elseif($orderby==7)
	$scl.=" order by a.sell_amount desc";
else
	$scl.=" order by a.rank desc,a.uptime desc";
//--------------------------------------------------
include_once("includes/page_utf_class.php");
$page = new Page;
$page->url=$config['weburl'].'/';
$page->listRows=16;
if(empty($cat['ext_field_cat']))
	$sql="SELECT a.* FROM ".PRO." a WHERE a.statu>0 $ext_sql $scl";
else
	$sql="SELECT a.*
	FROM ".PRO." a left join ".$cat['ext_table']." b on a.id=b.info_id and b.info_type='product' WHERE a.statu>0 $ext_sql $scl";

if(!$page->__get('totalRows'))
{
	$db->query($sql);
	$page->totalRows =$db->num_rows();
}
$sql.=" limit ".$page->firstRow.", 16";
//--------------------------------------------------
$db->query($sql);
$prol=$db->getRows();

include_once($config['webroot']."/module/product/includes/plugin_pro_class.php");
$pro = new pro();
foreach($prol as $keys=>$v)
{
	if($prol[$keys]['pic']!='')
	{
		$pic = $pro->pic_save_path.$pro->get_cover_img($prol[$keys]['pic']);
		$prol[$keys]['img'] = $pic;
		$prol[$keys]['big_img'] = substr($pic,0,strrpos($pic,'/')+1).'big_'.substr($pic,strrpos($pic,'/')+1);
	}
	else
	{
		$prol[$keys]['img'] = '/image/default/nopic.gif';
		$prol[$keys]['big_img'] ='/image/default/nopic.gif';		
	}
}
$prolist['list']=$prol;
$prolist['page']=$page->prompt();
$tpl->assign("info",$prolist);
unset($prolist);
//------------------------------------------------------

$tpl->assign("num",$page->totalRows);
$tpl->assign("subcat",is_numeric($id)?readsubcat($id,NULL,'all'):null);
$url=implode('&',convert($_GET));
$tpl->assign("url",$url);
//----------------------------SEO
$config['title']=str_replace('[catname]',$cat['cat'],$config['title2']);
$config['keyword']=str_replace('[catname]',$cat['cat'],$config['keyword2']);
$config['description']=str_replace('[catname]',$cat['cat'],$config['description2']);
//=====================================================
$tpl->assign("current","product");
include_once("footer.php");
$out=tplfetch("product_list.htm");
?>