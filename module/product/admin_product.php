<?php
include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");
//================================================================
$pro=new pro();

if(empty($_GET['catid'])&&empty($_GET['edit']))
{	
	$re=$admin->getCatName(PCAT);
	$tpl->assign("cat",$re);
	//------------------------------
	$tpl->assign("get_user_common_cat",$admin->get_user_common_cat($buid));
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_product_step1.htm",$flag,true);
}
else
{
	//-------------------------------------
	if($submit=="submit")
	{	
		$re=$pro->add_pro();
		if($re)
			$admin->msg("main.php?m=product&s=admin_product_list");
	}
	//-------------------------------------
	if($submit=="edit")
	{
		$re=$pro->edit_pro();
		if($re)
			$admin->msg("main.php?m=product&s=admin_product_list");
	}
	//------------------------------------
	$cre=explode('|',$config['credit']);
	foreach($cre as $key=>$v)
	{
		$nkey=pow(2,$key);
		$credit[$nkey]=$v;
	}
	
	if(!empty($_GET['edit']))
	{	
		$de=$pro->pro_detail($_GET['edit']);
		$de['credit']=explode_mi($de['credit'],$credit);
		$sql="select title from ".LGSTEMP." where id='$de[freight]'";
		$db->query($sql);
		$de['freight_name']=$db->fetchField('title');
		
		$tpl->assign("de",$de);
		$pactidlist=$de['catid'];
		if(!empty($de['tcatid']))
			$pactidlist.=",".$de['tcatid'];
		if(!empty($de['scatid']))
			$pactidlist.=",".$de['scatid'];	
		if(!empty($de['sscatid']))
			$pactidlist.=",".$de['sscatid'];
	}
	//--------------------------------
	if(empty($_GET['edit']))
	{
		$pactidlist=!empty($_GET['catid'])?$_GET['catid']:NULL;
		if(!empty($_GET['tcatid']))
			$pactidlist.= ",".$_GET['tcatid'];
		if(!empty($_GET['scatid']))
			$pactidlist.=",".$_GET['scatid'];
		if(!empty($_GET['sscatid']))
			$pactidlist.=",".$_GET['sscatid'];
	}
	$pro->add_user_common_cat($pactidlist);
	$tpl->assign("typenames",$pro->getProTypeName($pactidlist));
	$tpl->assign("brand",$pro->get_brand($pactidlist,$de['brand']));
	$tpl->assign("credit",$credit);
	$tpl->assign("ptype",explode('|',$config['ptype']));
	$tpl->assign("validTime",explode('|',$config['validTime']));
	$tpl->assign("custom_cat",$admin->get_custom_cat_list(1,0));
	
	$tpl->assign("prov",GetDistrict());
	//--------------------------自定义字段
	$nc=explode(",",$pactidlist);
	$now_catid=$nc[count($nc)-1];
	
	$sql="select ext_table,is_setmeal from ".PCAT." where catid='$now_catid'";
	$db->query($sql);
	$re=$db->fetchRow();
	$ext_table=$re['ext_table'];
	$is_setmeal=0;//$re['is_setmeal'];
	
	include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
	$addfield = new AddField('product');
	$extfiled=$addfield->addfieldinput($_GET['edit'],$ext_table);//
	$abc=$addfield->echoforeach('0',count($extfiled['d']));//
	$tpl->assign("firstvalue",$extfiled);
	$tpl->assign("is_setmeal",$is_setmeal);
	$tpl->assign("abc",$abc);
	
	
	//-----------物流模板---------
	$sql="select * from ".LGSTEMP." where userid='$buid'";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("lgs",$re);
	//==================================
	$nocheck=true;
	include_once("footer.php");
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	$output=tplfetch("admin_product.htm",$flag,true);
}

?>