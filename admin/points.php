<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");

//======================================
$sql="select * from ".POINTS." order by id";
$db->query($sql);
$re=$db->getRows();
if(empty($re))
{
	@set_time_limit(0);
	$fp=fopen("../install/points.sql","r");
	$sql=fread($fp,filesize("../install/points.sql"));
	fclose($fp);
	$ar=explode(";",$sql);
	foreach($ar as $ve)
	{
		if(!empty($ve))
		{
			$ve=str_replace("mallbuilder_",$config['table_pre'],$ve);
			$qre=$db->query($ve);
		}
	}
	admin_msg("points.php","数据导入成功");
}
if(!empty($_POST['submit']))
{
	foreach($_POST['pa'] as $key => $val)
	{
		$pb=trim($_POST['pb'][$key]);
		$id=trim($_POST['id'][$key]);
		$val=$val."|".$pb;
		$sql="update ".POINTS." set points='$val' where id='$id'";
		$db->query($sql);
		
	}
	admin_msg("points.php","设置成功");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('points');?></div>
  <div class="bigboxbody">
    <form action="" method="post">
      <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr class="theader">
          <td width="3%" align="left" ></td>
          <td width="260" >积分</td>
          <td></td>
        </tr>
        <?php
        foreach($re as $num=>$val)
        {
			$ar=explode('|',$val['points']);
		?>
        <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')" >
          <td align="left"><?php echo $val['id']; ?></td>
          <td><input type="hidden" name="id[<?php echo $val['id']; ?>]" value="<?php echo $val['id']; ?>" />
            <input class="ltext" type="text" name="pa[<?php echo $val['id']; ?>]" value="<?php echo $ar['0']; ?>" />
            -
            <input class="rtext" type="text" name="pb[<?php echo $val['id']; ?>]" value="<?php echo $ar['1']; ?>" /></td>
          <td><img src="../image/points/<?php echo $val['img']; ?>" /></td>
        </tr>
        <?php
		}
		?>
        <tr>
          <td><input class="btn" type="submit" name="submit" id="button" value="<?php echo lang_show('submit');?>"></td>
          <td colspan="2">&nbsp;
            <div class="page"><?php echo $pages?></div></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>