<?php
include_once("includes/news_function.php");
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=========================================
$id=is_numeric($_GET["id"])?$_GET['id']:die('Error');
$pagecurrent=!empty($_GET['page'])&&is_numeric($_GET['page'])?$_GET['page']:1;
//=========================================
session_start();

$db->query("update ".NEWSD." set onclick=onclick+1 where nid='$id'");
$db->query("select isgid,onclick,newstempid from ".NEWSD." where nid='$id'");
$nd=$db->fetchRow();
function insert_readCount()
{
	global $nd;
	return $nd['onclick'];
}


user_read_rec($buid,$id,4);//记当会员查看文章
//==============================
if($nd['newstempid']==1)
	$page='newspic.htm';
else
	$page='newsd.htm';

useCahe("news_detail/",true);
$flag=md5($dpid.$dcid.$config["temp"].$id.$pagecurrent);
if(!$tpl->is_cached($page,$flag))
{
	$sql="SELECT a.*,b.con FROM ".NEWSD." a left join ".NEWSDATA." b on a.nid=b.nid WHERE a.nid='$id'";
	$db->query($sql);
	$news=$db->fetchRow();
	$news['cat']=get_cat($news['classid']);
	
	$newsc=str_replace('<hr style="page-break-after:always;" class="ke-pagebreak" />',"[#page#]",$news['con']);
	$newsc=str_replace('<hr style="page-break-after:always;" class="ke-pagebreak" />',"[#page#]",$newsc);
	$articletext=array();
	$articletext=explode('[#page#]',$newsc);
	if($news['newstempid']==1)
	{
		$tpl->assign("newscon",$articletext);
		if(!empty($news['imgs_url']))
		{
			$array=	explode('|',$news['imgs_url']);
			foreach($array as $key=>$val)
			{	
				$news['imgs'][$key]=array('id'=>($key+1),'name'=>$val);
			}
		}
	}
	else
	{
		$nums=count($articletext);//算出数组长度
		$cp=$pagecurrent-1;
		$phref='';
		if($nums>1)
		{
			 for($i=1;$i<=$nums;$i++)
			 {
					if($i!=$pagecurrent)
					  $phref=$phref.'<a href="'.$config['weburl'].'/?m=news&s=newsd&id='.$id.'&page='.$i.'"><span style="border:#b6daeb 1px solid;background-color:#ffffff;display:moz-inline-box;display:inline-block;height:25px;width:25px;"><font size=4>'.$i.'</font></span></a>&nbsp;&nbsp;';
					else
					  $phref=$phref.'<span style="border:#ffd6b4 1px solid;background-color:#b6daeb;display:block;display:-moz-inline-box; display:inline-block; height:25px;width:25px;"><font size=4>'.$i.'</font></span>&nbsp;&nbsp;';
			 }
		}
	}
	$tpl->assign("pages",$phref);
	$tpl->assign("ncontent",$articletext[$cp]);
	
	//-------------------------------------------
	$ar1=array('[catname]','[title]','[keyword]','[des]');
	$ar2=array($news['cat'],$news['ftitle'],$news['keyboard'],strip_tags($prode['con']),$news['smalltext']);
	$config['title']=str_replace($ar1,$ar2,$config['title3']);
	$config['keyword']=str_replace($ar1,$ar2,$config['keyword3']);
	$config['description']=str_replace($ar1,$ar2,$config['description3']);
	//--------------------------------------------
	
	if($news['vote']!=',')
	{
		$sql="select * from ".NEWSVOTE." where id in ($news[vote]0)";
		$db->query($sql);
		$vote=$db->getRows();
		foreach($vote as $key=>$val)
		{
			if(strtotime($val['time'])-time()<0 and strtotime($val['time']))
			{
				$vote[$key]['end']='1';	
			}
			if($_COOKIE['vote'.$buid.$val['id']])
			{
				$vote[$key]['ip']='1';	
			}
			$str=explode('|',$val['votetext']);
			for($i=0;$i<count($str);$i++)
			{
				$vote[$key]['item'][$i]=explode(',',$str[$i]);
			}
		}
	}
	$tpl->assign("vote",$vote);
	$tpl->assign("de",$news);
	$tpl->assign("current","news");
	include_once("footer.php");
	$out=tplfetch($page,$flag);
	unset($news);
}
	

?>