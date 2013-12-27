<?php
	session_start();
	include_once("../includes/global.php");
	$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
	include_once($config['webroot']."/lang/".$config['language']."/admin.php");
	//=====================================
	$status=array('-1'=>lang_show('npass'),'0'=>lang_show('wpass'),'1'=>lang_show('pass'),'2'=>lang_show('rc')); 
	if(isset($_POST['statu']))
	{
		$cat=$_GET['t'];
		if($cat=='p')
			$table=PRO;

		$arr=explode(',',substr($_POST['de'],0,-1));
		if(!empty($_GET['id']))
		{
			$arr=array($_GET['id']);
		}
		
		foreach($arr as $val)
		{
			$sql="update ".$table." set statu='$_POST[statu]' where id = '$val'";
			$db->query($sql);
			$sql="select itemid from ".AUDIT." where itemid='$val'";
			$db->query($sql);
			if($db->fetchField('itemid'))
			{
				$sql="update ".AUDIT." set itemtype='$cat',argument='$_POST[con]',uid='$_SESSION[ADMIN_USER]',uptime='".time()."' where itemid='$val'";
			}
			else
			{
				$sql="insert into ".AUDIT."(itemid,itemtype,argument,uid,uptime) values('$val','$cat','$_POST[con]','$_SESSION[ADMIN_USER]','".time()."')";
			}
			$db->query($sql);
		}
	    unset($_GET['id']); 
		unset($_GET['t']);
		unset($_GET['m']); 
		unset($_GET['s']);
		unset($_GET['statu']);
		msg("module.php?m=product&s=prolist.php&".implode('&',convert($_GET)));
	}

?>

<script>
window.onload=function(){
	var array=window.parent.document.getElementsByName('de[]');
	var str="";
	for (var i = 0; i < array.length; i++)
	{
		 if(array[i].checked == true)
		 {
			 str=array[i].value+","+str;
		 }
	}
	
	document.getElementById('de').value=str;
}	
</script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<style>
.auditing .td1 u{ margin:0 3px; text-decoration:none; font-size:14px; color:#333; line-height:30px;}
.auditing .td2{ padding-left:5px; }
.auditing .td2 textarea{ height:126px; font-size:14px; line-height:16px; color:#444; border:1px solid #ddd; padding:2px;}
.auditing .td3{ padding:5px 0px 5px 4px; height:30px; }
.auditing .td3 input{ background:url(../image/default/btn_1.gif) no-repeat; width:57px; height:23px; line-height:23px; border:none; color:#FF5F00;}

</style>
<form action="" method="post" target="_parent" >
<input type="hidden" name="de" id="de" value="" />
<table cellpadding="0" cellspacing="0" border="0" width="350" class="auditing">
	<tr>
		<td class="td1">
			<?php
				foreach ($status as $key=>$val)
				{
					
					if($_GET['statu']==$key)
						$str="checked='checked'";
					else
						$str="";
					echo "<input type='radio' class='radio' name='statu' id='statu' value='".$key."' $str /><u>".$val."</u>";
				}
			?>
		</td>
	</tr>
	<tr>
		<td class="td2">
			<textarea name="con" id="con" rows="6" cols="42"><?php echo readauditing($_GET['id'],$_GET['t']); ?></textarea>
			<!--<select name="type" id="type" size="2" style=" height:130px; width:60px;"  >
				<option>11</option>
				<option>11</option>
				<option>11</option>
				<option>11</option>
			</select>-->
		</td>
	</tr>
	<tr>
		<td class="td3">
			<input type="submit" name="submit" id="submit" value="<?php echo lang_show('subsend');?>" />&nbsp;
			<input type="reset" value="<?php echo lang_show('subreset');?>" />
		</td>
	</tr>
</table>
</form>

