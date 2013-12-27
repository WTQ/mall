<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=================================================
if(!empty($_GET['action']))
{
	if($_GET['action']=='r')
		$db->query("REPAIR TABLE $_GET[name]");
	elseif($_GET['action']=='del')
		$db->query("DROP TABLE $_GET[name]");
	else
		$db->query("OPTIMIZE TABLE  $_GET[name]");
	admin_msg("optimizetable.php","操作成功");
}
if(!empty($_POST['submit'])==lang_show('repact'))
{
	foreach($_POST['optables'] as $v)
	{
		$sql="REPAIR TABLE  $v";
		$db->query($sql);
	}
	admin_msg("optimizetable.php","操作成功");
}
if(!empty($_POST['submit'])==lang_show('optact'))
{
	foreach($_POST['optables'] as $v)
	{
		$sql="OPTIMIZE TABLE  $v";
		$db->query($sql);
	}
	admin_msg("optimizetable.php","操作成功");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('tableoprep');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<script type="text/javascript">
function checkall()
{
	 for(var j = 0 ; j < document.getElementsByName("optables[]").length; j++)
	 {
	  	if(document.getElementsByName("optables[]")[j].checked==true)
	  	  document.getElementsByName("optables[]")[j].checked = false;
		else
		  document.getElementsByName("optables[]")[j].checked = true;
	 }
}
</script>
<form name="form1" method="post" action="">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('tableoprep');?></div>
	<div class="bigboxbody">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td height="16" colspan="7" align="left">
	    <p><?php echo lang_show('repairmsg');?></p>
        <p><?php echo lang_show('optimizemsg');?></p>
		<p>带有删除功能的表说明非标准系统正在用的表，如果您没有对系统进行过更改，没有自行加数据表，便可以删除。</p>
	  </td>
      </tr>
      <tr class="theader">
        <td width="4%"  align="left" ><input name="checktagall" type="checkbox" class="checkbox" class="STYLE1" id="checktagall" onClick="checkall()"></td>
        <td width="23%" align="left" ><?php echo lang_show('tname');?></td>
        <td width="20%" align="left" >编码</td>
        <td width="14%" align="left" >引擎</td>
        <td width="12%" align="left" >记录数</td>
        <td width="15%" align="left" >大小(K)</td>
        <td width="13%" align="center" >操作</td>
      </tr>
	  <?php 
		$define_array=get_defined_constants();
		foreach($define_array as $key=>$v)
		{
			if(substr($v,0,3)!=substr($config['table_pre'],0,3))
			unset($define_array[$key]);
		}
		$db->query("SHOW TABLE STATUS FROM ".$config['dbname']);
		$re=$db->getRows();
		foreach($re as $v)
		{
			if(substr($v['Name'],0,3)==substr($config['table_pre'],0,3))
			{$i++;
	  ?>
      <tr>
      <td align="left"><input name="optables[]" type="checkbox" class="checkbox" value="<?php echo $v;?>"></td>
	  <td align="left"><?php echo $v['Name'];?> </td>
      <td align="left"><?php echo $v['Collation'];?></td>
      <td align="left"><?php echo $v['Engine'];?></td>
      <td align="left"><?php echo $v['Rows'];?></td>
      <td align="left"><?php echo round($v['Data_length']/1024, 2);?></td>
      <td align="left">
	  <?php echo $is_use;?>
	  <a href="?action=r&name=<?php echo $v['Name'];?>">修复</a> | 
	  <a href="?action=o&name=<?php echo $v['Name'];?>">优化</a>
	  
	  <?php 
	  $ns=substr($v['Name'],0,strlen($config['table_pre'].'defind'));
	  $os=$config['table_pre'].'defind';
	  if(!in_array($v['Name'],$define_array)&&$ns!=$os )
	  {
	  ?>
	   | <a style="color:red;" href="?action=del&name=<?php echo $v['Name'];?>">删除</a>
	  <?php }?>
	  </td>
      </tr>
    <?php }} ?>
    <tr>
      <td colspan="7" align="left">
          <input name="submit" class="btn" type="submit" value="<?php echo lang_show('repact');?>">
          <input name="submit" class="btn" type="submit" value="<?php echo lang_show('optact');?>">
		  (共<?php echo $i;?>个表)
	  </td>
    </tr>
  </table>
  </div>
</div>
</form>
</div>
</div>
</body>
</html>