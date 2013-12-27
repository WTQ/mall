<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=============================
if(!empty($_GET['did']))
{
	$sql="delete from ".MENU." where id='$_GET[did]'";
	$db->query($sql);
}
if(!empty($_POST['submit']))
{
	foreach($_POST['id'] as $k5=>$v)
	{
		if(!empty($v))
		{
			$msql="update ".MENU." set sort='".$_POST['sort'][$k5]."',menu_name='".$_POST['menu_name'][$k5]."',link_addr='".$_POST['link_addr'][$k5]."',statu='".$_POST['statu'][$k5]."',selected_flag='".$_POST['selected_flag'][$k5]."' where id=$v";
			$db->query($msql); 
		}
		if(!empty($_POST['menu_name'][$k5])&&empty($v))
		{
			$sql="insert into ".MENU." (sort,menu_name,link_addr,statu,selected_flag,lang,type,partent_menu_id) 
			values ('".$_POST['sort'][$k5]."','".$_POST['menu_name'][$k5]."','".$_POST['link_addr'][$k5]."','".$_POST['statu'][$k5]."','".$_POST['selected_flag'][$k5]."','$config[language]','2','0')";
			$db->query($sql); 
		}
	}
	admin_msg('nav_menu.php','操作成功');
}
/****更新缓存文件*******/
$write_str=serialize(getipdata());//将数组序列化后生成字符串
$write_str='<?php $nav_menu = unserialize(\''.$write_str.'\');?>';//生成要写的内容
$fp=fopen('../config/nav_menu.php','w');
fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
fclose($fp);
/*********************/
function getipdata()
{
	global $db,$config;
	$sql="select menu_name,link_addr,selected_flag,type from ".MENU." 
		where (lang='$config[language]' or lang='') and partent_menu_id=0 and statu=1 order by sort asc";
	$db->query($sql);
	$re=$db->getRows();
	return $re;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<TITLE><?php echo lang_show('nav_Columns');?></TITLE>
</head>
<body>
<div class="guidebox"><?php echo lang_show('nav_config');?> </div>
<div class="bigbox">
<div class="bigboxhead"><?php echo lang_show('nav_config');?></div>
<div class="bigboxbody">
<form id="form1" action="nav_menu.php" method="POST" style="margin-top:0px;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr class="theader">
      <td width="114"  align="right"  ><?php echo lang_show('nav_sort');?></td>
      <td width="143" align="left"  ><?php echo lang_show('nav_name');?></td>
      <td width="442" align="left"  ><?php echo lang_show('nav_linkaddr');?></td>
      <td width="109" align="left"  ><?php echo lang_show('selflag');?></td>
	  <td align="left"  ><?php echo lang_show('nav_statu');?></td>
      <td width="109"  align="center"  ><?php echo lang_show('opert');?>&nbsp;</td>
    </tr>
	    <?php
		unset($sql);
        $sql="select * from ".MENU." where lang='$config[language]' and partent_menu_id=0 order by statu desc,sort asc";
        $db->query($sql);
        $re=$db->getRows();
        foreach($re as $v)
	    {
       ?>
        <tr>
          <td align="right" ><input name="sort[]" type="text" size="5" maxlength="2" value="<?php echo $v['sort'];?>"></td>
          <td><input name="menu_name[]" type="text" size="20" maxlength="30" value="<?php echo $v['menu_name'];?>"></td>
          <td><input name="link_addr[]" type="text" size="50" maxlength="100" value="<?php echo $v['link_addr'];?>"></td>
		 <td><input name="selected_flag[]" type="text" size="10" value="<?php echo $v['selected_flag'];?>" <?php if ($v['type']==1)echo "Readonly";?> />
		 <input name="id[]" type="hidden" value="<?php echo $v['id'];?>" />
		 </td>
		<td>
			<select name="statu[]">
			  <option value="1" <?php if($v['statu']==1) echo "selected";?>><?php echo lang_show('appaly');?></option>
			  <option value="0" <?php if($v['statu']==0) echo "selected";?>><?php echo lang_show('forbid');?></option>
			</select>
		</td>
          <td align="center" >
		  <?php 
          if($v['type']!=1)
          {
          ?><a href="nav_menu.php?action=del&did=<?php echo $v['id'];?>" onClick="javascript:return confirm('<?php echo lang_show('suredel');?>')"><?php echo $delimg;?></a>
          <?php
          }else{ echo $delimg;}
          ?>
		  </td>
        </tr>
		<?php
		}
		?>
		<tr>
		  <td align="right" >新增＋
		  <input name="id[]" type="hidden" value="" />
		  <input name="sort[]" type="text" size="5" maxlength="2" value="0"></td>
		  <td align="left" ><input name="menu_name[]" type="text"  size="20" maxlength="30"></td>
		  <td align="left" ><input name="link_addr[]" type="text" size="50" maxlength="100"></td>
		  <td align="left" ><input name="selected_flag[]" type="text" size="10" /></td>
		  <td align="left" ><select name="statu[]">
			  <option value="1" ><?php echo lang_show('appaly');?></option>
			  <option value="0" ><?php echo lang_show('forbid');?></option>
			</select></td>
		  <td align="left" >&nbsp;</td>
		</tr>
            <tr>
			  <td colspan="6" align="left" style="padding-left:40px;" >
				<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('submit');?>" onClick='return confirm("<?php echo lang_show('are_you_sure');?>");'>			  </td>
        </tr>
  </table>
</form>
</div>
</div>
</body>
</html>