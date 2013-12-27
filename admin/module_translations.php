<?php
	include_once("../includes/global.php"); 
	$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
	$sctiptName = array_pop($script_tmp);
	include_once("auth.php");

	if(empty($_POST))
	{
		$refer_lang = $_GET['code'] =='en'?'cn':'en';	//基本参照语言
		$l = $rl = array();echo "start";
		@include_once($config['webroot'].'/module/'.$_GET['mod'].'/lang/'.$_GET['code'].'.php');

		@eval('$l =$_LANG_MOD_'.strtoupper($_GET['mod']).';');	
		@include_once($config['webroot'].'/module/'.$_GET['mod'].'/lang/'.$refer_lang.'.php');
		@eval('$rl =$_LANG_MOD_'.strtoupper($_GET['mod']).';');

		$diff_lang = @array_diff_key($rl,$l);
		$l += $diff_lang; 
		if($l=='')
			die($lang['translat_data_emp']);
	}else{
		   if($config['enable_tranl']==0)
		  {
				die($lang['tranl_fordid']);
		  }
			include_once("../includes/lang_class.php");
			$tr_lang = new lang();
			foreach($tr_lang->module_files() as $key=>$mod)
			{	
				if(isset($_POST[strtolower($mod)]))
				{	
					$tr_lang->save_module_files( $_POST[strtolower($mod)],$key,$_GET['code'] );
					echo "<script>parent.window.succ_trans_tip('$key');</script>";
					break;
				}
			}
			die();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<fieldset><legend style="margin-left:25px;"><?php echo lang_show('translat_update');?></legend>
<form class='translationForm' target='tr_frame' onsubmit='tr_submit(this)' action="module_translations.php?<?php echo $_SERVER['QUERY_STRING']; ?>" method='post' >
	<table style="width:100%;TABLE-LAYOUT: fixed">
		<tr>
<?php $i=1; foreach($l as $key=>$v){  ?>
			<td align='right' width='30px'><?php echo $i.'. '; ?>&nbsp;</td>
			<td width='40%' class='pl'><dt class='field<?php if(var_export($v,TRUE)==''||isset($diff_lang["$key"])){ echo ' diff'; } ?>' <?php if($rl[$key]==NULL) echo "style='color:#FF9900;'";?>>{<?php echo "{$key}"; ?>}:&nbsp;</dt><dd><b><?php printf("%s",htmlspecialchars(is_array($rl[$key])?var_export($rl[$key],TRUE):$rl[$key])); ?></b></dd></td>
			<td>
			<?php if(strlen($v)<100&&!is_array($v)){ ?>
				<input type="text" name="_lang_mod_<?php echo $_GET['mod']."[{$key}]"; ?>" value="<?php if(isset($diff_lang["$key"])){echo '';}else{printf("%s",htmlspecialchars(stripslashes($v)));} ?>" />
			<?php }else{ ?>
				<textarea name='_lang_mod_<?php echo $_GET['mod']."[{$key}]"; ?>'><?php if(isset($diff_lang["$key"])){echo '';}else{printf("%s",htmlspecialchars(stripslashes(is_array($v)?var_export($v,TRUE):$v)));} ?></textarea>
			<?php } ?>
			</td>
<?php  echo "</tr><tr>"; $i++; }  ?>
<td colspan='2'>&nbsp;</td>
			<td align='left'>
				<input type='submit' style='width:50px; float:left;' value='<?php echo lang_show('submit'); ?>' /><span class='succ_tip' style='display:none;'>&nbsp;&nbsp;<?php echo lang_show('update_succ');?></span>
			</td>
		</tr>
	</table>
</form>
</fieldset>
