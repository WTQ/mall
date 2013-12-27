<?php
	include_once("includes/global.php");
	include_once("includes/smarty_config.php");
	include_once("$config[webroot]/lang/".$config['language']."/user_admin.php");
	include_once("includes/admin_class.php");
	//=================================
	$cat_table=PCAT;
	$module='product';
	$admin = new admin();
	if($buid)
		$tpl->assign("get_user_common_cat",$admin->get_user_common_cat($buid));//要改进一下，现在只是产品的分类。
	//ajax============================
	if(!empty($_GET['oper'])&&$_GET['oper']=='ajax')
	{
		switch($_GET['call'])
		{
			case 'search_cate':
				if(!empty($_POST['key_word'])){
					$wsql = NULL;
					$catlist = array();
					$key_words = explode(',',str_replace(' ','',$_POST['key_word']));

					if($key_words!=null)
					{
						foreach($key_words as $word)
						{
							if($word!=''){
								$word = addslashes( $word );
								$wsql[] =" ( cat like '%$word%' OR keyword like '%$word%' )";
							}
						}
						if($wsql!=NULL)
						{
							$wsql = implode(' or ',$wsql);
							$sql = "select catid from $cat_table where $wsql order by catid desc limit 0,10";
							$db->query($sql);
							
							while( $c = $db->fetchRow())
							{
								$vc = array();
								$v = $c['catid'];
								$vc[]=substr($v,0,4);
								if(strlen($v)>4)
									$vc[]=substr($v,0,6);
								if(strlen($v)>6)
									$vc[]=substr($v,0,8);
								if(strlen($v)>8)
									$vc[]=$v;
								$catlist[] = array( $v,implode(",",$vc) );
							}
						}
					}

					if($catlist!=null)
					{
						foreach($catlist as $key)
						{
							$cats[$key[0]] = $admin->getProTypeName($key[1]);
						}
					}
					echo json_encode($cats);

				}
				break;
			case 'get_cate':
				if(!empty($_POST['catid']))
				{
					$catid = $_POST['catid'];
					
					$sql = "select ext_table from $cat_table where catid='$catid'";
					$db->query($sql);
					$ext_table=$db->fetchField('ext_table');
					
					$cat['cat']='';
					$cat['brand']='';

					$vc[]=substr($catid,0,4);
					$pactidlist = substr($catid,0,4);
					if(strlen($catid)>4)
					{
						$vc[]=substr($catid,0,6);
						$pactidlist.= ",".substr($catid,0,6);
					}
					if(strlen($catid)>6){
						$vc[]=substr($catid,0,8);
						$pactidlist.= ",".substr($catid,0,8);
					}
					if(strlen($catid)>8){
						$vc[]=$catid;
						$pactidlist.= ",".$catid;
					}
					$catlist = implode(",",$vc);
					$cat['cat'] = $admin->getProTypeName( $catlist );
					
					if(file_exists($config['webroot'].'/module/product/'))
					{
						include_once("$config[webroot]/module/product/includes/plugin_pro_class.php");
						$pro = new pro();
						$cat['brand'] = $pro->get_brand($pactidlist);
					}
					
					
					include_once("$config[webroot]/module/product/includes/plugin_add_field_class.php");
					$addfield = new AddField($module);
					$cat['firstvalue'] = @implode('',$addfield->addfieldinput(0,$ext_table));
					
					echo json_encode($cat);
				}
				break;
		}
		die();
	}
	//==================================================
	$sql="select * from $cat_table where catid<9999 order by nums asc";
	$db->query($sql);
	$re=$db->getRows();
	$tpl->assign("cat",$re);
	$tpl->assign("config",$config);
	$tpl->assign("lang",$lang);
	//=================================================
	tplfetch("cate_show_ajax.htm",null,true);
?>