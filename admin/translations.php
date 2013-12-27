<?php
include_once("../includes/global.php"); 
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//##===================================================
if(!empty($_POST))
{
	if($config['enable_tranl']==0)
	{
		admin_msg("noright.php",$lang['tranl_fordid']);
	}
	include_once("../includes/lang_class.php");
	$t_lang= new lang();
	$code = $_POST['code'];

	switch($_POST['act'])
	{
		case 'export':
			$t_lang->to_export( $code,$_POST['type'] );
		case 'import':
			if( empty($_FILES['import_file']['tmp_name'])|| !preg_match('/\\.(gzip)$/i',$_FILES["import_file"]["name"])||filesize( $_FILES['import_file']['tmp_name'] )<10 )
			{
				admin_msg('translations.php',$lang['translat_file_err']);
			}
			$get_content = file_get_contents( $_FILES['import_file']['tmp_name'] );
			$t_lang->transla_import( $get_content,$code );
			unset( $get_content );
			admin_msg('translations.php',$lang['translat_im_ok']);
		default:
			break;
	}

}
function read_dir($dir)
{
	$i=0;
	$handle = opendir($dir); 
	$rdir=array();
	while ($filename = readdir($handle))
	{ 
		if($filename!="."&&$filename!=".."&&$filename!=".svn")
		{
		  if(is_dir($dir.$filename))
		  { 
		  	 if(substr($filename,0,5)!='user_'&&substr($filename,0,8)!='special_'&&substr($filename,0,5)!='email')
		   	 	$rdir[]=$filename;
		  }
	   }
	}
	return $rdir;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<style>
.bigboxbody td{border:none;}
#typeTranslationForm{
	display:block;
	padding:5px;
	line-height:21px;
}
#typeTranslationForm a{
	text-decoration:none;
}
fieldset{
	padding:3px 20px 30px 20px;
	border:1px dashed #CCCCCC;
	width:500px;
	margin:10px 0px 10px 30px;
}
fieldset p{
	padding:5px 0px;
}
</style>
<TITLE><?php echo lang_show('web_con_type');?></TITLE>
<script src="../script/jquery-1.4.4.min.js"  type="text/javascript"></script>
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('translations');?></div>
	<div class="bigboxbody">
	<fieldset><legend style="margin-left:25px;"><?php echo lang_show('translat_update');?></legend>	
	<form id="typeTranslationForm" action="type_translation.php" method="get">
			<p><?php echo lang_show('translat_modf_tip');?></p>
				<select style="float:left; margin-right:10px;" name="type">
					<option value="front"><?php echo lang_show('translat_front');?>&nbsp;</option>
					<option value="admin"><?php echo lang_show('translat_admin');?>&nbsp;</option>
					<option value="user_admin"><?php echo lang_show('translat_uadmin');?>&nbsp;</option>
					<option value="modules"><?php echo lang_show('translat_mod');?>&nbsp;</option>
					<option value="user_space"><?php echo lang_show('translat_uspace');?>&nbsp;</option>
				</select>
				<a href="javascript:chooseTypeTranslation('en')">
						<img title="en" alt="en" src="../image/cflag/england.gif">
					</a>&nbsp;
					</a>
					<a href="javascript:chooseTypeTranslation('cn')">
						<img title="cn" alt="cn" src="../image/cflag/cn.gif">
					</a>
					<input id="country" type='hidden'  name='code' autocomplete='0' value='' />
			</form>
		</fieldset>
		<fieldset><legend style="margin-left:25px;"><?php echo lang_show('translat_im');?></legend>	
	<form id="imTranslationForm" action="" enctype="multipart/form-data" onsubmit='return iFrm_check();'  method="post">
			<p><?php echo lang_show('translat_im_tip');?><font color='#FF9900'><?php echo lang_show('translat_im_wraning');?></font></p>
			<table><tr><td><?php echo lang_show('translat_im_opt');?></td>
			<td>
				<select style="float:left; margin-right:10px;width:150px;" name="code">
					<?php
					  $dir=read_dir('../lang/');
					  foreach($dir as $v)
					  {
						echo "<option value='$v'>$v</option>";
					  }
				  ?>
				</select>
				</td>
				<td>
				<input type="file" id='file' name="import_file">
				<input type="hidden" name="act" value='import'>
				<input class="btn" type="submit" name="submit" value='<?php echo lang_show('import');?>'>
				</td></tr></table>
			</form>
		</fieldset>
		<fieldset><legend style="margin-left:25px;"><?php echo lang_show('translat_ex');?></legend>	
	<form id="exTranslationForm" action="" method="POST">
			<p><?php echo lang_show('translat_ex_tip');?></p>
			<table><tr>
			<td><?php echo lang_show('translat_ex_opt');?></td>
			<td>
				<select style="float:left; margin-right:10px;width:120px;" name="type">
					<option value=""><?php echo lang_show('translat_ex_all');?>&nbsp;</option>
					<option value="front"><?php echo lang_show('translat_front');?>&nbsp;</option>
					<option value="admin"><?php echo lang_show('translat_admin');?>&nbsp;</option>
					<option value="user_admin"><?php echo lang_show('translat_uadmin');?>&nbsp;</option>
					<option value="modules"><?php echo lang_show('translat_mod');?>&nbsp;</option>
					<option value="user_space"><?php echo lang_show('translat_uspace');?>&nbsp;</option>
					<option value="payment"><?php echo lang_show('translat_payment');?>&nbsp;</option>
				</select>
				<select style="float:left; margin-right:10px;width:150px;" name="code">
					<?php
					  $dir=read_dir('../lang/');
					  foreach($dir as $v)
					  {
						echo "<option value='$v'>$v</option>";
					  }
				  ?>
				</select>
				<input type="hidden" name="act" value='export'>
				<input class="btn" type="submit" name="submit" value='<?php echo lang_show('export');?>'>
				</td></tr></table>
			</form>
		</fieldset>
	</div>
</div>
<script>
function chooseTypeTranslation(c){
	$('#country').val(c);
	document.getElementById('typeTranslationForm').submit();
}
function iFrm_check()
{
	if(document.getElementById('file').value==''){
		alert("<?php echo lang_show('ufile_tip_cho');?>");
		return false;
	}
}
</script>
</body>
</html>