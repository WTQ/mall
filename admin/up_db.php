<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//===========================================
@set_time_limit(0);
if(isset($_POST["action"]))
{
	$sql=str_replace("\\","",$_POST["sql"]);
	$ar=explode(";",$sql);
	foreach($ar as $ve)
	{
		$ve=trim($ve);
		if(!empty($ve))
		{
			$ve=str_replace("b2bbuilder_",$config['table_pre'],$ve);
			$ve=str_replace("mallbuilder_",$config['table_pre'],$ve);
			$ve=str_replace("hy_",$config['table_pre'],$ve);
			$qre=$db->query($ve);
		}
	}
	unset($_POST);
	unset($_GET);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<body>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('update_db');?></div>
<div class="bigbox">
<div class="bigboxhead"><?php echo lang_show('update_code');?></div>
<div class="bigboxbody">
<?php
if(isset($qre)&&$qre!=FALSE)
{
	admin_msg('up_db.php',lang_show('run_success'));
	unset($re);
}
else
{
?>
	<form name="form1" method="post" action="" style="margin-top:0px;">
	  <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr class="theader"> 
		  <td><?php echo lang_show('be_careful');?></td>
	    </tr>
		<tr> 
		  <td>
		  <?php
		  if(isset($qre)&&$qre==false)
			 echo "<font color='red'>".lang_show('sql_error')."</font><br>";
		  ?>
		    <textarea name="sql" cols="100" rows="20"></textarea><br />
		    <input class="btn" type="submit" id="button" value=" <?php echo lang_show('run_code');?> ">
            <input name="action" type="hidden" id="action" value="submit">
		  </td>
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