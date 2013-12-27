<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_MOD_DOWNLOAD; 
 $_LANG_MOD_DOWNLOAD = array (
  'comment_content' => '评论内容',
  'comment_delete' => '删除',
  'comment_grade' => '评论等级',
  'comment_list' => '评论列表',
  'comment_manag' => '管理',
  'comment_men' => '评论人',
  'comment_select' => '关键字',
  'comment_soso' => '搜索',
  'comment_time' => '发布时间',
  'comment_title' => '商品标题',
  'submit' => '搜索',
); 
  $lang = array_merge($lang, $_LANG_MOD_DOWNLOAD); 
?>