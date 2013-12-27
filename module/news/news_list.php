<?php
include_once("includes/global.php");
include_once("includes/smarty_config.php");
include_once("includes/news_function.php");
include_once("includes/page_utf_class.php");
//============头部菜单===========================================
$id=$_GET["id"];
$key=isset($_GET["key"])?trim($_GET["key"]):NULL;
$firstRow=!empty($_GET["firstRow"])?$_GET["firstRow"]:NULL;
$totalRows=!empty($_GET["totalRows"])?$_GET["totalRows"]:NULL;

if(isset($id))
{
	if(is_numeric($id))
	{
		$sql="SELECT * from ".NEWSCAT." WHERE catid='$id'";
		$db->query($sql);
		$catre=$db->fetchRow();
		$cat=$catre['cat'];
		if(!$cat)
		{
			header("Location: 404.php");die();
		}
	}
	else
	{
		header("Location: 404.php");die();
	}
	
	$classid=get_lowerid($id);
	$tsql=" and classid in ($classid)";
}
if(!empty($key))
	$tsql.=" and (title like '%$key%' or keyboard like '%$key%' or ftitle like '%key%')";

$sql="SELECT nid,title,ftitle,titleurl,titlefont,smalltext,uptime,titlepic,onclick,ispl FROM ".NEWSD." WHERE ispass=1 $tsql ORDER BY uptime DESC";
//-----------------------
$page = new Page;
$page->url=$config['weburl'];
$page->listRows=40;
if(!$page->__get('totalRows'))
{
	$db->query("SELECT count(*) as total FROM ".NEWSD." WHERE ispass=1 $tsql");
	$page->totalRows = $db->fetchField('total');
}
$sql.= "  limit ".$page->firstRow.", ".$page->listRows;
//-----------------------
$db->query($sql);
$re["page"]=$page->prompt();
$re["list"]=$db->getRows();
foreach($re["list"] as $key=>$val)
{
	if(empty($val['titleurl']))
		$re["list"][$key]['url']=$config["weburl"].'/?m=news&s=newsd&id='.$val['nid'];
	else
		$re["list"][$key]['url']=$val['titleurl'];
	if($val['titlefont'])
	{
		$st="style='";
		$font=explode('|',$val['titlefont']);	
		$col=array_pop($font);
		if(in_array('b',$font))
			$st.="font-weight:bold;";
		if(in_array('i',$font))
			$st.="font-style:italic;";
		if(in_array('s',$font))
			$st.="text-decoration:line-through;";
		if($col)
			$st.="color:$col";
		$st.="'";
		$re['list'][$key]['style']=$st;
	}	
	//$re["list"][$key]["keyboard"]=explode(',',$re["list"][$key]["keyboard"]);
}
$tpl->assign("re",$re);
$tpl->assign("cat",$cat);
$config['title']=str_replace('[catname]',$cat,$config['title2']);
$config['keyword']=str_replace('[catname]',$cat,$config['keyword2']);
$config['description']=str_replace('[catname]',$cat,$config['description2']);
//===========================================================
$tpl->assign("current","news");
include_once("footer.php");
if(!empty($catre['template']))
	$out=tplfetch($catre['template'],$flag);
else
	$out=tplfetch("news_list.htm",$flag);
?>