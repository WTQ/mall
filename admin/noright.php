<?php
	include_once("../includes/global.php");
	//============================================
	$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
	$sctiptName = array_pop($script_tmp);
	$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
	include_once($config['webroot']."/lang/".$config['language']."/admin.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo lang_show('use_manaul');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>
<div  style="width:400px; margin-left:auto; margin-right:auto;">
<div class="bigbox" style="margin-top:100px;">
	<div style="text-align:left; background-color:#E8F2FF; padding:8px; color:#0099CC; font-weight:bold;">系统提醒</div>
	<div style="background-color:#FFFFFF; border:1px solid #E8F2FF; padding:8px;">
	<?php
		if(empty($_GET['str']))
			echo lang_show('have_no_perm');
		else
		{
			echo '<div style="text-align:center; height:100px; padding-top:20px;">
			<font style="font-size:16px; color:green;font-weight:bold;line-height:30px;">'.$_GET['str'].'</font>';
			
			if(empty($_GET['url']))
				echo '<br><a style="text-decoration:none" href="javascript:history.back(-2);">点此返回</a>';
			else
			{
				echo '<br><a style="text-decoration:none" href="'.$_GET['url'].'">如果您的浏览器没有跳转，请点这里</a></div>';
			}
		}
	?>
	
	</div>
</div>
<script>
function gotourl(url)
{
	setTimeout("gotourl1('"+url+"')",1500);//设定超时时间
}
function gotourl1(url)
{
	window.location=url;
}
<?php if(!empty($_GET['url'])){ ?>
gotourl('<?php echo $_GET['url'];?>');
<?php } ?>
</script>
</div>
</center>
</body>
</html>
