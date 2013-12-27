<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//==========================================
if(isset($_GET['active']))
{
	$db->query("update ".CRON." set active='$_GET[active]' where id='$_GET[id]'");
}
if(isset($_GET['step']) && $_GET['step']=="del")
{
	$db->query("delete from ".CRON." where id='$_GET[deid]'");
}
if(isset($_GET['execute']) && $_GET['execute'] != '')
{
	include_once($config['webroot']."/includes/cron_inc.php");
	execute_transact($_GET['execute']);
	msg('crons.php',lang_show('executed'));
}
if(isset($_GET['submit']) && $_GET['submit'] == lang_show('submit'))
{
	if(isset($_GET['add']) && $_GET['add'] != '')
	{
		$sql = "insert into ".CRON." (name) value('$_GET[add]')";
		$db->query($sql);
	}
}
if(!empty($_GET['act'])&&$_GET['act']=='add')
{
	$sql="select id from ".CRON." where script='$_GET[script]'";
	$db->query($sql);
	if(!$db->fetchField('id'))
	{
		$admin_read_config=1;
		include($config['webroot'].'/includes/crons/'.$_GET['script']);
		$name=$cron_config['name'][$langs];
		if(empty($name))
			$name=$cron_config['name']['cn'];
		$sql = "insert into ".CRON." (name,script,week,day,hours,minutes,active)
				value
				('$name','$_GET[script]','$cron_config[week]','$cron_config[day]','$cron_config[hours]','$cron_config[minutes]','1')";
		$db->query($sql);
	}

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
<form action="" method="get">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('crons');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
	  <?php
	  	$sql = "select * from ".CRON." order by id";
		$db->query($sql);
		$re=$db->getRows();
		$coun_num=$db->num_rows();
		$script_ar=array();
		if($coun_num>0){
		?>
        <tr class="theader">
          <td width="208" align="left"><?php echo lang_show('cron_name');?></td>
          <td width="155" ><?php echo lang_show('cron_script');?></td>
          <td width="127" align="left" ><?php echo lang_show('frequency');?></td>
          <td width="168" ><?php echo lang_show('lasttransact');?></td>
          <td width="158" ><?php echo lang_show('nexttransact');?></td>
          <td width="114" align="center" ><?php echo lang_show('manager');?></td>
        </tr>
		<?php
		for($i=0;$i<$coun_num;$i++)
		{
			$script_ar[]=$re[$i]['script'];
		?>
			<tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
			  <td><?php echo $re[$i]['name']; ?></td>
			  <td><?php echo $re[$i]['script']; ?></td>
			  <td><?php
					if($re[$i]['week'] != -1) {
						$cycle = lang_show($re[$i]['week']);
					} else if($re[$i]['day'] == -1) {
						$cycle = lang_show('daily');
					} else {
						$cycle = lang_show('monthly');
						$cycle .= $re[$i]['day'];
						$cycle .= lang_show('day');
					}
					$cycle .= " ";
					$cycle .= $re[$i]['hours'];
					$cycle .= ":";
					$cycle .= $re[$i]['minutes'];
					echo $cycle;
		?></td>
          <td><?php if($re[$i]['lasttransact']) { echo date("Y-m-d",$re[$i]['lasttransact']); } else { echo "<b>N/A</b>"; } ?></td>
          <td><?php if($re[$i]['nexttransact']) { echo date("Y-m-d",$re[$i]['nexttransact']); } else { echo "<b>N/A</b>"; } ?></td>
          <td align="center">
          
		  <?php
		  if($re[$i]['active']==1)
		  	echo "<a href='?active=0&id=".$re[$i]['id']."'>$stopimg</a>";
		  else
		  	echo "<a href='?active=1&id=".$re[$i]['id']."'>$startimg</a>";
		  ?>
		  <a href="cron_edit.php?id=<?php echo $re[$i]['id']; ?>"><?php echo $editimg;?></a>

             <a href="?step=del&deid=<?php echo $re[$i]['id']; ?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>')"><?php echo $delimg;?></a>

		  <a href="?execute=<?php echo $re[$i]['id']; ?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>')"><?php echo $setimg;?></a>		   </td>
        </tr>
		<?php
		}}
		?>
      </table>

	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          	<?php
			$dir=$config['webroot'].'/includes/crons/';
			$handle = opendir($dir);
			$admin_read_config=1;$no_add=array();
			while($filename = readdir($handle))
			{ 
				if($filename!="."&&$filename!=".."&&$filename!='.svn')
				{
					if(!in_array($filename,$script_ar))
					{
						$no_add[]=$filename;
					}
			   }
			}
			if(count($no_add)){
			?>
		  <tr class="theader">
            <td align="left" style="border:none;">任务导入</td>
          </tr>
		   <tr>
            <td align="left">
			<?php
				foreach($no_add as $filename)
				{
					include_once($dir.$filename);
					$name=$cron_config['name'][$langs];
					if(empty($name))
						$name=$cron_config['name']['cn'];
					echo '<b>'.$name.'</b>&nbsp;[<a href="?act=add&script='.$filename.'">导入</a>]
					<br><span class="bz">'.$cron_config['des']['cn'].'</span><br>';
				}
			?>
			</td>
          </tr>
		  <?php } ?>
		  
		  <tr class="theader"><td><?php echo lang_show('add');?> </td></tr>
		 <tr>
            <td style="border:none;" align="left">
                <input class="text" type="text" name="add" value="" />
                <input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit'); ?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"><span class="bz">第一步填加任务名。第二步对填加的任务进行编辑，可以设置详细信息</span><br />
				
           </td>
          </tr>
      </table>
	</div>
</div>
</form>

</body>
</html>
