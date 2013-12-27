<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//==========================================
if(isset($_POST["action"]))
{
	$activenew = isset($_POST['active']) ? $_POST['active'] : 0;
	$sql = "update ".CRON." set  name='$_POST[name]',script='$_POST[script]',week='$_POST[week]',day='$_POST[day]',hours='$_POST[hours]',minutes='$_POST[minutes]',active='$activenew' where id='$_POST[id]'";
	$db->query($sql);
	include_once($config["webroot"]."/includes/cron_inc.php");
	update_transact($_POST);
	header("location:crons.php");
}
//===================
$db->query("select * from ".CRON." where id='$_GET[id]' ");
while($db->next_record())
{
	$id = $db->f('id');
	$name = $db->f('name');
	$script = $db->f('script');
	$weekold = $db->f('week');
	$dayold = $db->f('day');
	$hoursold = $db->f('hours');
	$minutesold = $db->f('minutes');
	$activeold = $db->f('active');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>

<form method="post" action="">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('crons');?></div>
	<div class="bigboxbody">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
               <td width="8%"><?php echo lang_show('cron_name');?></td>
               <td width="20%"><input type="text" name="name" value="<?php echo $name ?>" class="text"></td>
               <td></td>
             </tr>
             <tr>
               <td><?php echo lang_show('script');?></td>
               <td><input name="script" type="text" value="<?php echo $script ?>" class="text"></td>
			   <td class="bz"><?php echo lang_show('scriptlocation');?>/includes/crons/</td>
             </tr>
             <tr>
               <td><?php echo lang_show('weekly');?></td>
               <td><select name="week" class="select">
				<option value="-1"><?php echo lang_show('ignore');?></option>
			<?php	$weekdays=array('Sunday'=>lang_show('Sunday'),'Monday'=>lang_show('Monday'),'Tuesday'=>lang_show('Tuesday'),'Wednesday'=>lang_show('Wednesday'),'Thursday'=>lang_show('Thursday'),'Friday'=>lang_show('Friday'),'Saturday'=>lang_show('Saturday'),);
				foreach($weekdays as $key=>$v)
				{
				?>
                  <option value="<?php echo $key; ?>" <?php if($weekold==$key) echo "selected"; ?>><?php echo $v?></option>
                <?php
				}
				?>
                </select> </td>
				<td class="bz"><?php echo lang_show('setweekly');?></td>
             </tr>
             <tr>
               <td><?php echo lang_show('monthly');?></td>
               <td><select name="day" class="select">
				<option value="-1"><?php echo lang_show('daily');?></option>
			<?php 
				for($i=1;$i<=31;$i++)
				{
					$day = str_pad($i, 2, "0", STR_PAD_LEFT);
				?>
                  <option value="<?php echo $day; ?>" <?php if($dayold==$day) echo "selected"; ?>><?php echo $day?></day>
                <?php
				}
				?>
                </select></td>
				<td class="bz"><?php echo lang_show('setdaily');?></td>
             </tr>
             <tr>
               <td><?php echo lang_show('hour');?></td>
               <td><select name="hours" class="select">
			<?php 
				for($i=0;$i<=23;$i++)
				{
					$hours = str_pad($i, 2, "0", STR_PAD_LEFT);
				?>
                  <option value="<?php echo $hours; ?>" <?php if($hoursold==$hours) echo "selected"; ?>><?php echo $hours?></day>
                <?php
				}
				?>
                </select></td>
				<td></td>
             </tr>
             <tr>
               <td><?php echo lang_show('minutes');?></td>
               <td><select name="minutes" class="select">
			<?php 
				for($i=0;$i<=59;$i++)
				{
					$minutes = str_pad($i, 2, "0", STR_PAD_LEFT);
				?>
                  <option value="<?php echo $minutes; ?>" <?php if($minutesold==$minutes) echo "selected"; ?>><?php echo $minutes?></day>
                <?php
				}
				?>
                </select></td>
				<td></td>
             </tr>

             <tr>
               <td><?php echo lang_show('isactive');?></td>
               <td><input name="active" type="checkbox" class="checkbox" value="1" <?php if($activeold==1) echo "checked"; ?>/></td>
             </tr>
             <tr>
               <td>&nbsp;</td>
               <td><input class="btn" type="submit" name="submit" value="<?php echo lang_show('modify');?>"></td>
             </tr>
			 <input name="action" type="hidden" id="action" value="submit">
			 <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
           </table>
	</div>
</div>
</form>
</body>
</html>
