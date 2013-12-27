<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//============================================
if(!empty($_POST["cc"]))
{
	$password=md5($_POST['password']);
	$db->query("select * from ".ADMIN." where user='".$_SESSION["ADMIN_USER"]."' and password='$password'");
	$de=$db->fetchRow();
	if($de['user'])
	{
		$newpass=md5($_POST['newpass']);
		$db->query("update ".ADMIN." set password='$newpass'  where user='".$_SESSION["ADMIN_USER"]."'");
		echo "<br><p align=center>".lang_show('modok')."</p>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<body>
<form method="post" action="">
  <div class="guidebox"><?php echo lang_show('system_setting_home');?> &raquo; <?php echo lang_show('mod_password');?></div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('mod_password');?></div>
	<div class="bigboxbody">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="theader"> 
      <td width="15%" > 
        <?php echo lang_show('username');?></td>
      <td width="85%" > 
         <?php echo $_SESSION["ADMIN_USER"]; ?>
        </td>
    </tr>
    <tr> 
      <td width="15%" > 
        <?php echo lang_show('old_password');?></td>
      <td width="85%" > 
         <input class="text" type="password" name="password" value="">
         </td>
    </tr>
    <tr> 
      <td width="15%" > 
        <?php echo lang_show('new_password');?></td>
      <td width="85%" > 
        <input class="text" type="password" name="newpass">
        </td>
    </tr>
    <tr> 
      <td width="15%" > 
        <?php echo lang_show('repeat_new_password');?></td>
      <td width="85%" > 
         <input class="text" type="password" name="repass">
        </td>
    </tr>
    <tr> 
      <td width="15%" >&nbsp; </td>
      <td width="85%" > 
        <input class="btn" type="submit" name="cc" value="<?php echo lang_show('submit');?>">
       </td>
    </tr>
  </table>
  </div>
</div>
</form>
</body>
</html>
