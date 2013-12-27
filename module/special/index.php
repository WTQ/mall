<?php
/**
 * Copyright :http://www.b2b-buildr.com
 * Powered by :B2Bbuilder
 */
include_once("includes/global.php");
include_once("includes/smarty_config.php");
//=============================================================
       //最新专题
       $sql="select * from ".SPE." order by add_time desc";
	   $db->query($sql);
	   $re=$db->getRows();
	   $tpl->assign("new_spe",$re);
	   
	   //推荐专题
	   $sql="select * from ".SPE." order by `order` desc";
	   $db->query($sql);
	   $re=$db->getRows();
	   $tpl->assign("rec_spe",$re);
	   
	   //热门专题
	   $sql="select * from ".SPE." order by readcount desc";
	   $db->query($sql);
	   $re=$db->getRows();
	   $tpl->assign("rm",$re);
//=============================================================  
$tpl->assign("current","special");
include_once("footer.php");
$out=tplfetch("special_index.htm");
?>