<?php
	session_start();
	include_once("../includes/global.php");
	$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
	include_once($config['webroot']."/lang/".$config['language']."/admin.php");
	//=====================================
	if(empty($_GET['status']))
		$status=array('-1'=>lang_show('npass'),'0'=>lang_show('wpass'),'1'=>lang_show('pass'),'2'=>lang_show('rc')); 
	else
	{
		$status_list = explode( '|',stripslashes( urldecode( $_GET['status'] ) ) );		//$_GET['status']="-1,不通过|0,待审核|1,审核通过";
		if( $status_list )
		{
			foreach( $status_list as $var )
			{
				$s = explode( ',',$var );
				$stat_str[] = "'$s[0]'=>'$s[1]'";
			}
			$stat_str = implode( ',',$stat_str );
			eval('$status = array('.$stat_str.');');
		}
	}
?>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script>
	function audit_post(id)
	{
		var v = [];
		var status= document.getElementById('statu_list').getElementsByTagName('input');
		if($('#send_mail').attr('checked')==false)
		{
			$('#mail_title').val('');
			$('#mail_con').val('');
		}
		for(var i=0;i<status.length;i++)
		{
			if(status[i].checked==true)
			{
				id>0?window.parent.setAudit(status[i].value,id,$('#mail_title').val(),$('#mail_con').val()):window.parent.setAudit(status[i].value,id,$('#mail_title').val(),$('#mail_con').val());
				return;
			}
		}
	}
</script>
<link href="main.css" rel="stylesheet" type="text/css" />
<style>
.auditing .td1 label{ margin:0 3px; text-decoration:none; font-size:14px; color:#333; line-height:30px;}
.auditing .td1 input{ vertical-align:sub !important;vertical-align:middle}
.auditing .td2{padding-left:5px;}
.auditing .td2 textarea{ height:153px; width:100%; font-size:12px; line-height:16px; color:#444; border:1px solid #ddd; padding:2px;}
.auditing .td3{padding:5px 0px 5px 4px; height:30px;}
</style>
<form action="" method="post"  onsubmit="audit_post(<?php echo isset($_GET['id'])?$_GET['id']:0;?>);return false;">
<input type="hidden" name="de" id="de" value="" />
<table cellpadding="0" cellspacing="0" border="0" width="350" class="auditing" style="margin:0 auto;">
	<tr>
		<td class="td1" id='statu_list'>
			<?php
				foreach ($status as $key=>$val)
				{
					if($_GET['statu']==$key)
						$str="checked='checked'";
					else
						$str="";
					echo "<label><input type='radio' class='radio' name='statu' id='statu' value='".$key."' $str />".$val."</label>";
				}
			?>
		</td>
	</tr>
	<tr>
		<td class="td2">
			<input style="display:none" class="text" name="mail_title" id="mail_title" value="<?php $mail_temp=get_mail_template('review_info'); echo $mail_temp['subject']; ?>" />
			<textarea class="text" style="display:none; margin-top:5px; height:126px;" name="mail_con" id="mail_con" rows="6" cols="42"><?php echo $mail_temp['message'];?></textarea>
			<textarea name="con" id="con" rows="10" cols="42"></textarea><br />
			<input id="send_mail" name="send_mail" type="checkbox" value="1" /><span class="bz">发送邮件通知</span>
		</td>
	</tr>
	<tr>
		<td class="td3">
			<input class="btn" type="submit"  id="submit" value="<?php echo lang_show('subsend');?>" />&nbsp;
			<input class="btn" type="reset" value="<?php echo lang_show('subreset');?>" />
		</td>
	</tr>
</table>
</form>
<script>
$(document).ready(function(){
   			$("#send_mail").click(function()
			{	
				if($('#send_mail').attr("checked")==true)
				{
					$('#mail_con').show();
					$('#mail_title').show();
					$('#con').hide();
				}
				else
				{
					$('#mail_con').hide();
					$('#mail_title').hide();
					$('#con').show();
				}

   			});	
   		});
</script>