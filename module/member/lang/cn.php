<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_MOD_MEMBER; 
 $_LANG_MOD_MEMBER = array (
 
  'itype'=>array('普通发票','增值税发票'),
  'irise'=>array('个人','单位'),
  'icontent'=>array('明细','办公用品（附购物清单）','电脑配件','耗材'),
); 
$lang = array_merge($lang, $_LANG_MOD_MEMBER); 
?>