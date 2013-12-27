<?php
include("module/brand/includes/plugin_brand_class.php");
//=====================
 $mybrand=new brand();
 $re=$mybrand->brand_index_list();
//---------------
	$sql="select * from ".BRANDCAT." where parent_id ='0' order by displayorder";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$val){
		
		$sql="select * from ".BRANDCAT." where parent_id=$val[id] order by displayorder ";
		$db->query($sql);
		$de = $db->getRows();
		$re[$key]['list']=$de;
	}
	$tpl->assign("bcat",$re);
//===========================
$tpl->assign("brand",$re);
$tpl->assign("current","brand");
include("footer.php");
$out=tplfetch("brand_index.htm");
?>