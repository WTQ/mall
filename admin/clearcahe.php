<?php
@set_time_limit(0);
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//================================================
if(!empty($_POST['de']))
{
	foreach($_POST['de'] as $v)
	{
		del_cache($v);
	}
	admin_msg('clearcahe.php',lang_show('clear_cache_res'));
}
//=================================================
$cache_dir[0][0]="../cache/ad/";
$cache_dir[0][1]='广告';

$cache_dir[1][0]="../cache/news_detail/";
$cache_dir[1][1]='新闻';

$cache_dir[2][0]="../cache/apidata/";
$cache_dir[2][1]=lang_show('label');

$cache_dir[3][0]="../cache/keyword/";
$cache_dir[3][1]='关键字';

$cache_dir[4][0]="../cache/shop/";
$cache_dir[4][1]='会员店铺';

$cache_dir[5][0]="../cache/front/";
$cache_dir[5][1]=lang_show('onstage');

$cache_dir[6][0]="../templates_c/";
$cache_dir[6][1]=lang_show('smarty_c');

$cache_dir[7][0]="../cache/special/";
$cache_dir[7][1]=lang_show('spe');
function del_cache($dir)
{
	$i=0;
	$handle = opendir($dir); 
	while ($filename = readdir($handle))
	{ 
		if($filename!="."&&$filename!=".."&&$filename!='.svn')
		{
		  if(!is_dir($dir.$filename))
		  {
			unlink($dir.$filename);
		  }
		  else
		  	del_cache($dir.$filename."/");
	   }
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
<form action="" method="post">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('clear_cache');?></div>
	<div class="bigboxbody">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"  >
    <?php foreach($cache_dir as $v){?>
    <tr>
      <td width="25" align="left">
      <input name="de[]" type="checkbox" class="checkbox" value="<?php echo $v[0];?>"></td>
	  <td width="*%" align="left"><?php echo $v[1];?> </td>
    </tr>
    <?php } ?>
    <tr>
      <td height="20" align="left"><label>
        <input onClick="do_select()" type="checkbox" class="checkbox" name="checkbox" id="checkbox">
      </label>        </td>
      <td align="left"><input name="submit" class="btn" type="submit" value="<?php echo lang_show('submit');?>">
        <?php 
	  if(!empty($_POST['cl_cache'])&&$_POST['cl_cache']==1)
	  	echo lang_show('clear_cache_res');
	  ?></td>
    </tr>
  </table>
  </div>
</div>
</form>
</body>
</html>