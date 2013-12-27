<?php
include_once("../../includes/global.php");
include_once("../../includes/admin_class.php");
include_once("../../includes/smarty_config.php");
//===============================================
$action=isset($_GET['action'])?$_GET['action']:NULL;
$admin = new admin();
switch ($action)
{
	case "admin_list":
	{
		include_once($config['webroot']."/module/product/includes/plugin_pro_class.php");
		$pro=new pro();
		if(!empty($_GET['rec']))
		{
			echo $pro->rec_pro($_GET['rec'],$_GET['type']);
			die();
		}
		
		if(isset($_GET['update']))
		{
			$pro  ->  update_pro($_GET['update']*1);
			echo 1;
		}
		break;
	}
	case "admin_product":
	{
		
		if(isset($_GET['rec']))
		{
			$db->query("delete from  ".SETMEAL." where id='$_GET[rec]'");
			echo 1;
		}
		break;
	}
	case "step1":
	{
		if(isset($_GET['name']))
		{
			list($catid, $tcatid, $scatid, $sscatid) = explode(',', $_GET['name']);
			
			$list_array				= array();
			$list_array['done']		= true;
			$list_array['one']		= '';
			$list_array['two']		= '';
			$list_array['three']	= '';
			$list_array['four']	    = '';
			
			if($catid > 0)
			{
				
				$sql="select * from ".PCAT." where catid<9999 order by nums asc";
				$db->query($sql);
				$re=$db->getRows();
				foreach ($re as $val)
				{
				
					if($val['catid'] == $catid)
					{
						$list_array['one']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|1"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
					else
					{
						$list_array['one']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|1"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
				}	
					
				$class_list=readsubcat($catid,NULL,'all');
				foreach ($class_list as $val)
				{
				
					if($val['catid'] == $tcatid)
					{
						$list_array['two']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|2"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
					else
					{
						$list_array['two']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|2"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
				}	
			}
			if($tcatid > 0)
			{
				$class_list=readsubcat($tcatid,NULL,'all');
				
				foreach ($class_list as $val)
				{
				
					if($val['catid'] == $scatid)
					{
						$list_array['three']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|3"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
					else
					{
						$list_array['three']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|3"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
				}	
			}
			if($scatid > 0)
			{
				$class_list=readsubcat($scatid,NULL,'all');
				
				foreach ($class_list as $val)
				{
				
					if($val['catid'] == $sscatid)
					{
						$list_array['four']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|4"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
					else
					{
						$list_array['four']	.= '<li class="" onclick="selClass(this);" id="'.$val['catid'].'|4"> <a class="" href="javascript:void(0)"><span class="has_leaf">'.$val['cat'].'</span></a> </li>';
					}
				}	
			}
		}
		echo json_encode($list_array);
		break;
	}
}
?>