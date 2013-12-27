<?php

	include_once("config.php");
	
	if($_POST["act"]=='save')
	{
		$wmark_type='0';
		foreach($_POST as $pname=>$pvalue)
		{
			if ($pname!="act")
			{
				if($pname=="wmark_locaction" and $pvalue=='0')
				{
					$wmark_type='1';
				}
				if($pname=="wmark_type" and $wmark_type)
				{
					$pvalue='0';
				}
				unset($sql);
				$sql="select * from ".CONFIG." where `index`='$pname'";
				$db->query($sql);
				if($db->num_rows())
				{
				   $sql1=" update ".CONFIG." SET value='$pvalue' where `index`='$pname'";
				}
				else
				{
				   $sql1="insert into ".CONFIG." (`index`,value) values ('$pname','$pvalue')";
				}
				$db->query($sql1);
				$configs[$pname]=$pvalue;
			}
		}
		
		if($_GET['operation']=='watermark')
		{
			$name="watermark_config";
		}
		elseif($_GET['operation']=='remote')
		{
			$name="remote_config";
		}
		else
		{
			$name="image_config";
		}
		/****更新缓存文件*********/
		$write_config_con_str=serialize($configs);//将数组序列化后生成字符串
		$write_config_con_str='<?php $'.$name.' = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
		$fp=fopen('../config/'.$name.'.php','w');
		fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
		fclose($fp);
		/*********************/
		
		if($_GET['operation']=='watermark')
		{
			if($_POST['wmark_locaction']!=0 or ($_POST['wmark_type']==2 and !empty($_POST['wmark_words']) and !empty($_POST['wmark_words_color'])))
			{
				$img="../uploadfile/preview/cat_preview.jpg";
				$from = "../uploadfile/preview/cat.jpg";
				@unlink($img);
				if(file_exists($from))
				{
					@copy($from,$img);
					if(file_exists($img))
						makethumb($img,"../uploadfile/preview/cat_preview.jpg",600,300);
				}
			}
		}
		admin_msg("upload_config.php?operation=".$_GET['operation'],'更新成功');
	}
  	
	if($_GET['operation']=='watermark')
	{
		include_once("../config/watermark_config.php");
		//水印位置
		$watermark_locaction=array(
			'1' => '顶端居左',
			'2' => '顶端居中',
			'3' => '顶端居右',
			'4' => '中部居左',
			'5' => '中部居中',
			'6' => '中部居右',
			'7' => '底端居左',
			'8' => '底端居中',
			'9' => '底端居右'
		);
		$watermark=0;
		if(file_exists("../uploadfile/preview/cat_preview.jpg"))
		{
			$watermark=1;
		}
		$tpl->assign("watermark",$watermark);
		$tpl->assign("watermark_locaction",$watermark_locaction);
		$tpl->assign("watermark_config",$watermark_config);
	}
	elseif($_GET['operation']=='remote')
	{
		@include_once("../config/remote_config.php");
		$tpl->assign("remote_config",$remote_config);
	}
	else
	{
		@include_once("../config/image_config.php");
		//当前服务器环境,最大允许上传
		$upload_max_filesize=ini_get('upload_max_filesize');
		$tpl->assign("upload_max_filesize",$upload_max_filesize);
		
		//图片存放类型
		$image_storage_type=array(
			'1'=>'按年月日存放（例如：店铺ID/年/月/日/图片）',
			'2'=>'按年月存放（例如：店铺ID/年/月/图片）',
			'3'=>'按年存放（例如：店铺ID/年/图片）',
			'4'=>'按文件名存放（例如：店铺ID/图片）'
		);
		$tpl->assign("image_storage_type",$image_storage_type);
		$tpl->assign("image_config",$image_config);
	}
	$tpl->display("upload_config.htm");
?>