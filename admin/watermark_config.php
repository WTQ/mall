<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
include_once("../config/watermark_config.php");
//=========================================入库操作
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('submit'))
{
	foreach($_POST as $pname=>$pvalue)
	{
		if ($pname!="submit")
		{
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
	/****更新缓存文件*********/
	$write_config_con_str=serialize($configs);//将数组序列化后生成字符串
	$write_config_con_str='<?php $wmark_config = unserialize(\''.$write_config_con_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/watermark_config.php','w');
	fwrite($fp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	fclose($fp);
	/*********************/
	if((isset($configs['wmark_type']) && $configs['wmark_type'] == 1) && (!$configs['wmark_image'] || !file_exists("../image/default/".$configs['wmark_image']))){
		admin_msg(" watermark_config.php?not_exsit=1",'水印文件不存在');
	}else{
		admin_msg(" watermark_config.php?update=1",'更新成功');
	}
	exit;
}
if(isset($_GET['update']) && isset($wmark_config['wmark_type']))
{
	if( ($wmark_config['wmark_type']==1 && isset($wmark_config['wmark_image']))|| ($wmark_config['wmark_type']==2 && isset($wmark_config['wmark_words']))|| $wmark_config['wmark_type']==0)
	{
		$from = "../uploadfile/preview/cat.jpg";
		@unlink('../uploadfile/preview/cat_preview.jpg');
		if(file_exists($from))
		{
			makethumb($from,"../uploadfile/preview/cat_preview.jpg",600,300);
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
</head>
<body>
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('wmark_config');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('wmark_config');?></div>
	<div class="bigboxbody">
	<form name="sysconfig" action="watermark_config.php" method="post" style="margin-top:0px;">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" >	
        <tr class="theader">
          <td  colspan="3" align="left" ><?php echo lang_show('wmark_config_msg');?></td>
        </tr>
		<tr>
          <td width="306" ><input type="radio" class="radio" onClick="wmarkTypeChange(this.value);" name="wmark_type" id="wmark_type1" value="1" <?php
		  if (isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==1)
			echo "checked";
		  ?>><label for="wmark_type1"><?php echo lang_show('wmark_type_image');?></label></td><td width="277" ><input type="radio" class="radio" onClick="wmarkTypeChange(this.value);" name="wmark_type" id="wmark_type2" value="2" <?php
		  if (isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==2)
			echo "checked";
		  ?>><label for="wmark_type2"><?php echo lang_show('wmark_type_words');?></label></td><td width="403" ><input type="radio" class="radio" onClick="wmarkTypeChange(this.value);" name="wmark_type" id="wmark_type3" value="0" <?php
		  if (isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==0)
			echo "checked";
		  ?>><label for="wmark_type3"><?php echo lang_show('close_wmark');?></label></td>
        </tr>
</table>
   
	  <div id="type2" <?php if(isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==2) echo ""; else echo "style='display:none'"; ?>>
	  <table width="100%">
        <tr>
          <td width="50" align="left" ><span class="STYLE17"><?php echo lang_show('wmark_words_con');?></span></td>
          <td width="300" align="left" >
            <input name="wmark_words" type="text" id="wmark_words" size="30" maxlength="50" value="<?php if(isset($wmark_config['wmark_words'])) echo $wmark_config['wmark_words'];?>">
            <span class="STYLE15"><?php echo lang_show('wmark_words_des');?>
			</span></td>

          <td width="50" align="left" ><?php echo lang_show('wmark_words_color');?></td>
          <td width="300" align="left" >
            <input name="wmark_words_color" type="text" id="wmark_words_color" size="30" maxlength="60"	value="<?php if(isset($wmark_config['wmark_words_color'])) echo $wmark_config['wmark_words_color'];?>">
			<span class="STYLE15"><?php echo lang_show('wmark_color_des');?></span></td>
        </tr>
		</table>
	  </div>

      	  <div id="type1" <?php if(isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==1) echo ""; else echo "style='display:none'"; ?>>
	  <table width="100%">
<?php
if(!isset($_GET['not_exsit']) && isset($wmark_config['wmark_image']))
{
?>
	<tr>
	  <td align="left"><img src="../image/default/<?php echo $wmark_config['wmark_image'];?>?a=<?php echo time().rand(0,100);?>" border="0" /></td>
	</tr>
	<?php
}
?>
    <tr>
      <td align="left">
     <?php echo lang_show('wmark_image_path');?>
        /image/default/<input name="wmark_image" type="text" id="wmark_image" size="15" maxlength="60" value="<?php if(isset($wmark_config['wmark_image'])){ echo $wmark_config['wmark_image'];}?>">
    <?php echo lang_show('wmark_image_des');?>
    </td>
    </tr>
    </table>
		</div>    
   <div id="type_location" <?php if(isset($wmark_config['wmark_type']) && $wmark_config['wmark_type']==0) echo "style='display:none'"; else echo ""; ?>>
	  <table width="100%">
         <tr class="theader">
          <td colspan="3" width="517" height="34" align="left" ><?php echo lang_show('wmark_location');?></td>
        </tr>
		</tr>
          <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction1" value="1" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==1) echo "checked"; ?>> <label for="wmark_locaction1"><?php echo lang_show('wmark_location1');?></label> </td>

		  <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction2" value="2" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==2) echo "checked"; ?>><label for="wmark_locaction2"><?php echo lang_show('wmark_location2');?></label></td>

		  <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction3" value="3" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==3) echo "checked"; ?>><label for="wmark_locaction3"><?php echo lang_show('wmark_location3');?></label></td>
		</tr>

		</tr>
          <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction4" value="4" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==4) echo "checked"; ?>> <label for="wmark_locaction4"><?php echo lang_show('wmark_location4');?></label> </td>

		  <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction5" value="5" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==5) echo "checked"; ?>><label for="wmark_locaction5"><?php echo lang_show('wmark_location5');?></label></td>

		  <td><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction6" value="6" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==6) echo "checked"; ?>><label for="wmark_locaction6"><?php echo lang_show('wmark_location6');?></label></td>
		</tr>

		</tr>
          <td ><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction7" value="7" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==7) echo "checked"; ?>> <label for="wmark_locaction7"><?php echo lang_show('wmark_location7');?></label> </td>

		  <td ><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction8" value="8" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==8) echo "checked"; ?>><label for="wmark_locaction8"><?php echo lang_show('wmark_location8');?></label></td>

		  <td ><input type="radio" class="radio" name="wmark_locaction" id="wmark_locaction9" value="9" <?php if (isset($wmark_config['wmark_locaction']) && $wmark_config['wmark_locaction']==9) echo "checked"; ?>><label for="wmark_locaction9"><?php echo lang_show('wmark_location9');?></label></td>
		</tr>
		</table>
	  </div> 
    
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		 <tr>
			<td colspan="3">
			<?php echo lang_show('submit_and_preview');?><input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>">
			</td>
		</tr>
		<?php
		if(file_exists('../uploadfile/preview/cat_preview.jpg')){
		?>
		<tr><td colspan="3" align="left">
		<img src="../uploadfile/preview/cat_preview.jpg?time=<?php echo time();?>" border="0" />
		</td></tr>
		<?php }?>
	</table>
	</form>
	</div>
</div>

<script type="text/javascript">
<?php 
if(isset($_GET['not_exsit']) && $_GET['not_exsit'])
{
?>
   alert("<?php echo lang_show('wmark_image_not_exsit');?>");
<?php
}
?>
function wmarkTypeChange(typeValue){
	if(typeValue==0){
		document.getElementById("type1").style.display="none";
		document.getElementById("type2").style.display="none";
		document.getElementById("type_location").style.display="none";
	}else if(typeValue==1){
		document.getElementById("type1").style.display="";
		document.getElementById("type2").style.display="none";
		document.getElementById("type_location").style.display="";
	}else if(typeValue==2){
		document.getElementById("type1").style.display="none";
		document.getElementById("type2").style.display="";
		document.getElementById("type_location").style.display="";
	}
}
</script>
</body>
</HTML>