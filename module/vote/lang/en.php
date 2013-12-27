<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_MOD_VOTE; 
 $_LANG_MOD_VOTE = array (
  'add' => '添加投票',
  'addvote' => '添加投票',
  'bres' => '推荐',
  'click' => '总票数',
  'del' => '删除',
  'editvote' => '修改投票',
  'field' => 
  array (
    0 => '不限字段',
    1 => '标题',
    2 => '副标题',
    3 => '内容简介',
    4 => '发布者',
    5 => 'ID',
  ),
  'id' => 'ID',
  'limitip' => '限制IP',
  'limitips' => 
  array (
    0 => '不限制',
    1 => '限制',
  ),
  'nbres' => '取消推荐',
  'operation' => '操作',
  'rec' => '推',
  'search' => '搜索',
  'time' => '发布时间',
  'title' => '标题',
  'type' => '类型',
  'vote_types' => 
  array (
    0 => '单选',
    1 => '多选',
  ),
  'vote' => '投票管理',
  'voteitem' => '投票项目',
  'votelimit' => '限制IP',
  'votelimit_arr' => 
  array (
    0 => '不限制',
    1 => '限制',
  ),
  'votelimit_show' => '(限制后同一IP只能投一次票)',
  'votename' => '投票标题',
  'votetime' => '过期时间',
  'votetime_show' => '(超过此期限,将不能投票,空为不限制)',
  'votetype' => '投票类型',
  'votetype_arr' => 
  array (
    0 => '单选',
    1 => '多选',
  ),
); 
  $lang = array_merge($lang, $_LANG_MOD_VOTE); 
?>