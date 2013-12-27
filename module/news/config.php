<?php

//=====================管理员后台===============
$mem['running'][1][]=array(
	'新闻系统',
	array(
		'news_step.php,1,news,发布新闻',
		'news.php,0,news,发布新闻',
		'newscat.php,1,news,新闻类别',
		'news_module.php,1,news,文章标签',
		'module_config.php,1,news,模块设置',
		'newslist.php,1,news,新闻管理',
	),	
);
/*$arr[0]='newslist.php,1,news,新闻管理';
$sql="select catid,cat from ".NEWSCAT." where pid=0 order by nums asc";	
$db->query($sql);
$re=$db->getRows();
foreach($re as $key=>$val)
{
	$arr[$key+1]="newslist.php&classid=$val[catid],1,news,$val[cat],";	
}

$mem[6][1][]=array('新闻管理',$arr);
*/?>