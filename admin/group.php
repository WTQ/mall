<?php
include_once("../includes/global.php");
include_once("../lang/" . $config['language'] . "/admin.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
@include_once("auth.php");
//=========================================
if($_SESSION['province']||$_SESSION['city'])
	msg('noright.php');

if(!empty($_POST['group_name'])&&empty($_GET['id']))
{
	if(is_array($_POST['perm']))
		$per=implode(",",$_POST['perm']);
	else
		$per='';
	$sql="insert into ".GROUP." (group_name,group_perms,group_desc) VALUES ('$_POST[group_name]','$per','$_POST[group_desc]')";
	$re=$db->query($sql);
	if($re)
		msg("group_list.php");
}
if(!empty($_POST['group_name'])&&!empty($_GET['id']))
{
	if(isset($_POST['perm'])&&is_array($_POST['perm']))
		$per=implode(",",$_POST['perm']);
	else
		$per='';
	$sql="update ".GROUP." set
	      group_name='$_POST[group_name]',group_perms='$per',group_desc='$_POST[group_desc]'
		  where group_id='$_GET[id]'";
	$re=$db->query($sql);
	if($re)
		msg("group_list.php");
}
//=========================================
if(!empty($_GET['id']))
{
	$sql="select * from ".GROUP." where group_id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
	$group_perms=explode(",",$de['group_perms']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<title><?php echo lang_show('business_manager_system');?></title>
</head>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('add_group');?></div>
  <div class="bigboxbody">
    <form name="form1" method="post" action="">
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
          <td width="10%"><?php echo lang_show('group_name');?></td>
          <td><input  class="text" type="text" name="group_name" value="<?php echo $de['group_name']; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo lang_show('descript');?></td>
          <td><textarea name="group_desc" class="text" rows="5"><?php echo $de['group_desc']; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo lang_show('perm');?></td>
          <td><?php
			include_once("menu_config.php");
			foreach($mem as $key=>$v)
			{
				
				if(is_array($group_perms)&&in_array(md5($key),$group_perms))
					$str_check="checked";
				else
					$str_check="";
					
				echo '<table width="100%">';
				echo '<tr><td style=" font-size:16px;"><b>&nbsp;<input '.$str_check.' type="checkbox" class="checkbox" name="perm[]" value="'.md5($key).'" />&nbsp;&nbsp;'.$v[0].'</b></td></tr>';
				echo '<tr><td>';
				foreach($v[1] as $ssv)
				{
					
					echo '<div class="mod">';
					echo '<div class="stitle">'.$ssv[0].'</div><ul class="part4">';
					foreach($ssv[1] as $sv)
					{
						$sna=explode(",",$sv);
						$str_check="";
						if(is_array($group_perms)&&in_array(md5($sna[0]),$group_perms))
							$str_check="checked";
						$scrp_name=substr($sna[0],0,-4);
						
						if(!empty($sna[3]))
							$sname=$sna[3];
						else
							$sname=$lang[$scrp_name];
						if(!empty($sname))
						{	
							echo '<li><label><input '.$str_check.' type="checkbox" class="checkbox" name="perm[]" value="'.md5($sna[0]).'" />&nbsp;'.$sname;
							echo '</label></li>';
						}
					}
					echo '</ul></div>';
				}
				echo '</td></tr>';
				echo '</table>';
			}
			?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input  class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>