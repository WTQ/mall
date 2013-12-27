<?php
include_once("../includes/global.php"); 
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");

include_once("../includes/lang_class.php");
$t_lang =  new lang();
$refer_lang = $_GET['code'] =='en'?'cn':'en';	//基本参照语言

if(empty($_POST)){
	$l = array();
	$type = $_GET['type'];

	if($_GET['type']!='modules')
	{
		$gen_files = $t_lang->gen_files;
		if(array_key_exists($type,$gen_files))
		{
			if( $type=='admin')
			{
					if( $_GET['code'] == $config['language'] )
					{
						eval('$l = $'.$gen_files[$type].';');
						include_once($config['webroot']."/lang/$refer_lang/$type.php");
						eval('$rl = $'.$gen_files[$type].';');
						$lang = array_merge($lang, $l); 
					}
					else
					{
						$rl = $admin_lang = $_LANG_ADMIN;
						unset($_LANG_ADMIN);
						include_once($config['webroot'].'/lang/'.$_GET['code'].'/admin.php');
						eval('$l = $'.$gen_files['admin'].';');
						$_LANG_ADMIN = $admin_lang;
					}
			}
			else
			{
					include_once($config['webroot'].'/lang/'.$_GET['code']."/$type.php");
					eval('$l = $'.$gen_files[$type].';');
					include_once($config['webroot']."/lang/$refer_lang/$type.php");
					eval('$rl = $'.$gen_files[$type].';');
			}
		}
		
		$diff_lang = array_diff_key($rl,$l);
		$l += $diff_lang; 
		if($l=='')
			die($lang['translat_data_emp']);
	}
}
elseif( !empty( $_POST["_lang_".$_GET['type']] ) )		//更改全局翻译
{
	if($config['enable_tranl']==0)
	{
		admin_msg("noright.php",$lang['tranl_fordid']);
	}
	$t_lang->save_generate_files( $_POST["_lang_".$_GET['type']],$_GET['type'],$_GET['code'] );
	admin_msg("translations.php",$lang['update_succ']);	
}else{
	die('Empty content.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<style>
.translationForm{
	display:block;
	padding:5px;
	line-height:30px;
}
.translationForm a{
	text-decoration:none;
}
.translationForm input,.translationForm textarea{
	width:300px;
	display:block;
}
.translationForm textarea{
	height:100px;
}
.translationForm .pl{
	text-align:left;
	letter-spacing: 1px;
	word-wrap:break-word;
	overflow:hidden;
}
form dt,dd{
	float:left;
}
form dd{
	max-width:280px;
}
.pl b{
	word-wrap:break-word; 
	overflow:hidden;
}
.diff{
	color:red;
}
.tr_title{
	background:#FFDAC8;
	padding:0px 5px;
}
.tr_title b{
	padding-left:10px;
	font-size:13px
}
fieldset{
	padding:3px 20px 30px 20px;
	border:1px dashed #CCCCCC;
	width:720px;
	margin:10px 0px 10px 30px;
}
#mod_list{
	padding:10px 5px;
	overflow:hidden;
}
.mod{
	margin-top:10px;
	overflow:hidden;
}
.mod_title{
    height: 26px;
}
.bigboxbody .mod_title a{
	background-image: url("../image/admin/edit.gif");
    background-position: right center;
    background-repeat: no-repeat;
	font-size: 13px;
	padding-right:12px;
    font-weight: bold;
}
.bigboxbody .mod_title a:hover{
	font-size:13px;
}
.bigboxbody .mod_title .mod_on{
	background: url("../image/default/decrease.gif") right center no-repeat;
	color:#006699;
	font-size:13px;
}
.succ_tip{
		background: url("../image/default/answer.gif") right center no-repeat;
		color:#006699;
		float:left;
		padding-right:15px;
}
</style>
<TITLE><?php echo lang_show('web_con_type');?></TITLE>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script>
	var submit = "<?php echo lang_show('submit'); ?>";
	function succ_trans_tip(m){
		$('#module_'+m).find('input[type=submit]').val(submit);
		$('#module_'+m).find('input[type=submit]').css('width','50px');
		$('#module_'+m).find('input[type=submit]').attr('disabled',false);
		$('#module_'+m).find('.succ_tip').show();
	} 
	function tr_submit(frm){
			$(this).find('.succ_tip').hide();
			var btn = $(this).find('input[type=submit]');
			btn.css('width','80px');
			btn.val('<?php echo lang_show('update_now');?>');
			btn.attr('disabled',true);
	}
	$(document).ready(function() { 

		$("#mod_list .mod_title a").click(function() {
			if(!$('#module_'+$(this).attr('ref')).html())
			{	
				$(this).toggleClass('mod_on');
				$('#module_'+$(this).attr('ref')).load($(this).attr('href'));
			}
			else
			{
				$(this).toggleClass('mod_on');
				$('#module_'+$(this).attr('ref')).empty();
			}
			return false;
		});
    });
</script>
</head>
<body>
<div class="bigbox">
	
	<div class="bigboxhead"><?php echo lang_show('translat_update');?></div>
	<div class="bigboxbody">
	
	<?php 
		if($_GET['type']=='modules')
		{
	?>
	<iframe name='tr_frame' border=0 style='display:none;'></iframe>
	<?php
//-----mod_list-----------------
			echo "<div id='mod_list'>";
			$mod_index = 1;
			$mod_list = $t_lang->module_files();
			foreach($mod_list as $mod=>$val)
			{
	?>
			<div class='mod'>
				<div class='mod_title'><?php echo $mod_index++;?>.<a class='' ref='<?php echo $mod;?>' href="module_translations.php?mod=<?php echo $mod;?>&code=<?php echo $_GET['code'];?>"><?php echo $mod;?>&nbsp;<?php echo lang_show('module');?></a></div>
				<div class='mod_disp' id="module_<?php echo $mod;?>"></div>
			</div>
	<?php
			}
		echo '</div>';
//-----mod_list end----------------
		}
		else
		{
	?>
	<form id="typeTranslationForm" class='translationForm' action="" method="post">
				<div class='tr_title'><b><?php echo lang_show('translat_'.$_GET['type']);?></b><span><?php echo lang_show('translat_lack_1');?>&nbsp;<label class='diff'><?php echo count($diff_lang);?></label>&nbsp;<?php echo lang_show('translat_lack_2');?></span></div>
				<table style="width:100%;TABLE-LAYOUT: fixed">
				<tr>
<?php $i=1; foreach($l as $key=>$v){  ?>
					<td align='right' width='30px'><?php echo $i.'. '; ?></td>
					<td width='40%' class='pl'><dt class='field <?php if(var_export($v,TRUE)==''||isset($diff_lang["$key"])){ echo ' diff'; } ?>' <?php if($rl[$key]==NULL) echo "style='color:#FF9900;'";?> >{<?php echo "{$key}"; ?>}:&nbsp;</dt><dd><b><?php printf("%s",htmlspecialchars(stripslashes(is_array($rl[$key])?var_export($rl[$key],TRUE):$rl[$key]))); ?></b></dd></td>
					<td>
					<?php if(strlen($v)<100&&!is_array($v)){ ?>
						<input type="text" name="<?php echo "_lang_".$type."[$key]"; ?>" 
						value="<?php if(isset($diff_lang["$key"])){echo '';}else{ printf("%s",htmlspecialchars(stripslashes($v)));} ?>" />
					<?php }else{ ?>
						<textarea name="<?php echo "_lang_".$type."[$key]"; ?>"><?php if( isset($diff_lang["$key"]) ){echo '';}else{ printf("%s",htmlspecialchars(stripslashes(is_array($v)?var_export($v,TRUE):$v))); } ?></textarea>
					<?php } ?>
					</td>
<?php  echo "</tr><tr>"; $i++; }  ?>
				<td colspan='3' align='center'>
				<input style="width:50px;" class="btn" type='submit' value='<?php echo lang_show('submit'); ?>' /></td>
				</tr>
				</table>
	</form>
	<?php 
		}
	?>
	</div>
</div>
</body>
</html>