<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=============================
if(!empty($_POST['submit']))
{
	foreach($_POST['sort'] as $k=>$v)
	{
		if(empty($_POST['con_group'][$k]))
			$_POST['con_group'][$k]=0;
			
		$msql="update ".WEBCON." set con_no='$v',con_group='".$_POST['con_group'][$k]."',con_title='".$_POST['menu_name'][$k]."',con_statu='".$_POST['statu'][$k]."',con_linkaddr='".$_POST['con_linkaddr'][$k]."' where con_id=$k";
		$db->query($msql); 
	}
}
if (!empty($_POST['webcontitle'])&&!empty($_POST['addwebcon']))
{
	if(empty($_POST['add_group']))
		$_POST['add_group']=0;
	$msql="insert into ".WEBCON." (con_no,con_statu,con_title,con_group,con_linkaddr,lang)
	values 
	('0','1','$_POST[webcontitle]','$_POST[add_group]','$_POST[linkaddr]','$config[language]')";
	$db->query($msql); 
}
if (!empty($_GET['action'])&&$_GET['action']=='del'&&!empty($_GET['did']))
{
	$msql="delete from ".WEBCON." where con_id='$_GET[did]'";
	$db->query($msql); 
}
/****更新缓存文件*******/

function getwebdata()
{
	global $db,$config;
	$sql="select * from ".WEBCON." where con_statu=1 and lang='$config[language]' order by con_no asc";
	$db->query($sql);
	$re=$db->getRows();
	$li=array();
	foreach($re as $key=>$v)
	{
		if(!empty($v['con_linkaddr']))
			if(substr($v['con_linkaddr'],0,4)=='http')
				$url=$v['con_linkaddr'];
			else
				$url=$config['weburl'].'/'.$v['con_linkaddr'];
		else
			$url=$config['weburl']."/aboutus.php?type=".$v['con_id'];
		$li[$key]="<a href='$url'>".$v['con_title']."</a>";
	}
	return implode(" | ",$li);
}
//===================================
$sql="select * from ".WEBCONGROUP." where lang='$config[language]'";
$db->query($sql);
$group =$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<TITLE><?php echo lang_show('web_con_type');?></TITLE>
</head>
<body>
<div class="bigbox" <?php if($_GET['act']=='add') echo 'style="display:block;"';else echo 'style="display:none;"';?>>
	<div class="bigboxhead tab" style=" width:90%">
		<span class="cbox"><a href="web_con_type.php">管理页面</a></span>
		<span class="cbox on"><a href=""><?php echo lang_show('webcontype');?></a></span>
		<span class="cbox"><a href="web_con_group.php">页面分组</a></span>
    </div>
	
	<div class="bigboxbody" style="margin-top:-1px;">
	<form id="form1" action="web_con_type.php" method="POST">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td class="searh_left"><?php echo lang_show('con_title');?></td>
      <td align="left" >
        <input type="text" class="text" name="webcontitle" id="webcontitle" />
      </td>
	  </tr>
      <tr>
        <td><?php echo lang_show('laddr');?></td>
        <td align="left" >
          <input type="text" class="text" name="linkaddr" id="linkaddr" />
        </td>
      </tr>
      <tr>
        <td>选择分组</td>
        <td align="left" >
          <select class="select" name='add_group'>
            <option value=''>选择分组</option>
            <?php foreach($group as $g){ ?>
            <option value="<?php echo $g['id']; ?>"><?php echo $g['title']; ?></option>
            <?php } ?>
          </select>[<a href="web_con_group.php">分组管理</a>]</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left" ><input class="btn" type="submit" name="addwebcon" id="addwebcon" value="<?php echo lang_show('addtype');?>" /></td>
      </tr>
		</table>
		</form>
	</div>
</div>


<div class="bigbox" <?php if($_GET['act']=='') echo 'style="display:block;"';else echo 'style="display:none;"';?> >
<div class="bigboxhead tab" style=" width:90%">
	<span class="cbox on"><a href="#">管理页面</a></span>
	<span class="cbox"><a href="web_con_type.php?act=add"><?php echo lang_show('webcontype');?></a></span>
	<span class="cbox"><a href="web_con_group.php">页面分组</a></span>
</div>
<div class="bigboxbody" style="margin-top:-1px;">
<form id="form2" action="web_con_type.php" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
    <tr class="theader">
      <td width="53" align="left"><?php echo lang_show('sno');?></td>
      <td width="128" align="left" ><?php echo lang_show('stitle');?></td>
      <td width="98" align="left" >分组</td>
      <td align="left">调用地址</td>
      <td width="104" align="left">模板</td>
      <td width="100" align="left" ><?php echo lang_show('tp');?></td>
	  <td width="68" align="left" ><?php echo lang_show('statu');?></td>
	  <td width="110" align="left" ><?php echo lang_show('act');?></td>
    </tr>
	    <?php
		
		if(!empty($_GET['group']))
			$tsql.=" and con_group='$_GET[group]' ";
		
		$sql="select * from ".WEBCON." where 1 $tsql and lang='$config[language]' order by con_no";
		$db->query($sql);
		$re=$db->getRows();
        foreach($re as $v)
	    {
       ?>
        <tr>
          <td >
		  <input name="sort[<?php echo $v['con_id'];?>]" type="text" id="sort<?php echo $v['con_id'];?>" size="5" maxlength="2" value="<?php echo $v['con_no'];?>">
		  </td>
          <td >
		  <input name="menu_name[<?php echo $v['con_id'];?>]" type="text" id="menu_name<?php echo $v['con_id'];?>" size="20" maxlength="30" value="<?php echo $v['con_title'];?>">		  </td>
          <td >
		  <select style='width:80px' name='con_group[<?php echo $v['con_id'];?>]'>
			<option value=''>选择分组</option>
			<?php foreach($group as $g){ ?>
				<option <?php if($v['con_group']==$g['id']) echo 'selected'; ?> value="<?php echo $g['id']; ?>"><?php echo $g['title']; ?></option>			
			<?php } ?>
		  </select>
		  </td>
          <td >
		  <?php if($v['con_linkaddr']){ ?>
    <input name="con_linkaddr[<?php echo $v['con_id'];?>]" type="text" size="30" value="<?php if($v['con_linkaddr']) echo $v['con_linkaddr'];?>" >
    <?php } else {?>
	<input name="con_linkaddr[<?php echo $v['con_id'];?>]" type="text" size="30" value="<?php echo "$config[weburl]/aboutus.php?type=".$v['con_id']; ?>">
	<?php } ?>
		  </td>
          <td ><?php echo empty($v['template'])?'Default':$v['template'];?></td>
          <td ><?php 
      if ($v['con_type']=='1')
          echo lang_show('sys');
      else
          echo lang_show('comsu'); 

      ?>  </td>
          <td align="left" >
            <select name="statu[<?php echo $v['con_id'];?>]" id="statu<?php echo $v['con_id'];?>">
              <option value="1" <?php if($v['con_statu']==1) echo "selected";?>><?php echo lang_show('ison');?></option>
              <option value="0" <?php if($v['con_statu']==0) echo "selected";?>><?php echo lang_show('isoff');?></option>
          </select>		  </td>
          <td align="left" >
		  &nbsp;<a href="web_con_set.php?con_id=<?php echo $v['con_id'];?>"><?php echo $editimg;?></a>
          <a href="web_con_type.php?action=del&did=<?php echo $v['con_id'];?>" onClick="javascript:return confirm('<?php echo lang_show('suredel');?>')"><?php echo $delimg;?></a></td>
        </tr>
    <?php
    }
    ?>
            <tr>
          <td colspan="8" align="left" valign="top">
            <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('submit');?>" onClick='return confirm("<?php echo lang_show('suredel');?>");'>			</td>
        </tr>
  </table>
</form>
</div>
</div>
</body>
</html>