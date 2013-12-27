<?php
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//============================================
if(isset($_GET['id']))
{
	$sql="SELECT * FROM ".ADVSCON." WHERE ID='$_GET[id]'";
	$db->query($sql);
	$re=$db->fetchRow();
	$date_list = array('day'=>1,'week'=>7,'month'=>30);
	$re['etime'] = 0<$re['etime']?$re['etime']:time()+$re['show_time']*$date_list[$re['unit']]*24*3600;
}
if(isset($_GET['delpic']))
{
	$sql="update ".ADVSCON." set picName='' where ID='$_GET[id]'";
	$db->query($sql);
	@unlink("../uploadfile/ads/$_GET[id]");
	msg("edit_adv_con.php?id=$_GET[id]&type=$_GET[type]&group=$_GET[group]");
}
//==================================================
if(!empty($_FILES['pic'])&&is_uploaded_file($_FILES['pic']['tmp_name']))
{
	$pn=explode(".",$_FILES['pic']['name']);
	$ftype=$pn[count($pn)-1];
	$time=time();
	if($ftype=="swf")
		$pname=$_GET['id'].$time.".swf";
	elseif($ftype=="gif")
		$pname=$_GET['id'].$time.".gif";
	else
		$pname=$_GET['id'].$time.".jpg";
	copy($_FILES['pic']['tmp_name'],"../uploadfile/ads/$pname");
	$subsql=",picName='$pname'";
}
if(isset($_POST['submit'])&&isset($_GET['id']))
{	
	$_POST['isopen']*=1;
	$_POST['stime']="'".strtotime($_POST['stime'])."'";
	$_POST['etime']="'".strtotime($_POST['etime'])."'";
	$_POST['catid']*=1;
	
	if(!empty($_POST['catid']))
		$subsql.=",catid='$_POST[catid]'";
	else
		$subsql.=",catid='0'";
		
	$sql="UPDATE ".ADVSCON." SET
			   url='$_POST[url]',type='$_POST[type]',isopen='$_POST[isopen]',stime=$_POST[stime],etime=$_POST[etime]
			   ,con='$_POST[con]',name='$_POST[name]',width='$_POST[width]',height='$_POST[height]' $subsql
		  WHERE
		   		ID=$_GET[id]";
	$re=$db->query($sql);
	if($re==true)
		msg("advs_con_list.php?group_id=".$_GET['group_id']."&group=$_GET[group]");
}
elseif(isset($_POST['submit'])&&!isset($_GET['id']))
{
	$_POST['isopen']*=1;
	$_POST['catid']*=1;
	$_POST['stime']="'".strtotime($_POST['stime'])."'";
	$_POST['etime']="'".strtotime($_POST['etime'])."'";
	
	$sql="insert into ".ADVSCON." (group_id,url,`type`,isopen,con,picName , `stime` , `etime`,name,province,city,area,width,height,catid)
		  values ('$_GET[group_id]','$_POST[url]','$_POST[type]','$_POST[isopen]','$_POST[con]','$pname',$_POST[stime],$_POST[etime],'$_POST[name]','$_SESSION[province]','$_SESSION[city]','$_SESSION[area]','$_POST[width]','$_POST[height]','$_POST[catid]')";
	if($db->query($sql)==true)
		msg("advs_con_list.php?group_id=".$_GET['group_id']."&group=$_GET[group]");
}
if(empty($_GET['type']))
	$_GET['type']=1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
</HEAD>
<style>
.imgs img{max-height:300px; max-width:300px}
</style>
<body>
<form action="" method="post" enctype="multipart/form-data" name="form1">
<div class="bigbox">
	<div class="bigboxhead">
	<span style="float:left"><?php  if(isset($_GET['id']))echo lang_show('advedit');else echo lang_show('advadd');?><?php echo lang_show('advertisement');?></span>
	</div>
	<div class="bigboxbody"><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><?php echo lang_show('adv_type');?></td>
    <td>
	<script>
	function goto(id)
	{
		str='<?php if(isset($_GET['id'])) echo "&id=$_GET[id]";echo "&group_id=$_GET[group_id]";?>';
		window.location='edit_adv_con.php?type='+id+str;
	}
	</script>
	<select class="select" name="type" onChange="goto(this.value)">
      <option value="1" <?php if($_GET['type']==1) echo "selected";?>><?php echo lang_show('text');?></option>
	  <option value="2" <?php if($_GET['type']==2) echo "selected";?>><?php echo lang_show('code');?></option>
      <option value="3" <?php if($_GET['type']==3) echo "selected";?>><?php echo lang_show('image');?></option>
	  <option value="4" <?php if($_GET['type']==4) echo "selected";?>><?php echo lang_show('flash');?></option>
    </select>
	</td>
    </tr>
  <tr>
    <td><?php echo lang_show('adv_name');?></td>
    <td><input class="text" type="text" name="name" value="<?php if (isset($re['name'])) echo $re['name'];?>"/>
	<span class="bz">如果此广告是在列表处展示，将用于搜索词定位显示。如搜索词为“手机”，广告名称也为“手机”，将会匹配展示</span>
	</td>
    </tr>
  <tr>
    <td>类别ＩＤ</td>
    <td><input name="catid" class="text" type="text" value="<?php if(!empty($re['catid'])) echo $re['catid'];?>" id="catid">
	<span class="bz">如果此广告是在列表处展示，支持按类别进行广告投放，将信息分类的ＩＤ号填入此处，如果广告不是在列表页展示，请保持为空</span>
	</td>
  </tr>
  <?php
  if($_GET['type']==1||$_GET['type']==2)
  {
  ?>
  <tr>
    <td><?php echo lang_show('adv_content');?></td>
    <td><textarea name="con" class="text" rows="8" id="con"><?php if (isset($re['con'])) echo $re['con'];?></textarea><span class="bz">广告展示内容</span></td>
    </tr>
	<?php
	}
	if($_GET['type']==1||$_GET['type']==3)
	{
	?>
	<tr>
    <td><?php echo lang_show('link_url');?></td>
    <td><input class="text" name="url" type="text" value="<?php if (isset($re['url'])) echo $re['url'];else echo 'http://';?>" size="70" /><span class="bz">以http://开头，完整网址</span></td>
    </tr>
	<?php
	}
	if($_GET['type']==3||$_GET['type']==4)
	{
	?>
  <tr>
    <td><?php echo lang_show('image');?>/flash</td>
    <td class="imgs"><?php
	  if(!empty($re['picName'])&&isset($_GET['type'])&&$_GET['type']==3)
	  {
	  	echo "<img src=../uploadfile/ads/$re[picName] border=0><br>";
		echo "<a href='?id=$_GET[id]&delpic=true&type=$_GET[type]'>".lang_show('delete')."</a>";
	  }
	  elseif(isset($re['type'])&&$re['type']==4)
	{
		$str='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0">'.
		'<param name="movie" value="'.$config['weburl']."/uploadfile/ads/".$re['picName'].'" />'.
		'<param name="quality" value="high" />'.
		'<embed width="100%" height="100%" src="'.$config['weburl']."/uploadfile/ads/".$re['picName'].'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>'.
		'</object>';
		echo $str;
	}
	  else
	  {
	  ?>
      	<input name="pic" type="file" id="pic" />
	  <?php } ?>
	 </td>
    </tr>
<?php
}
if($_GET['type']==4)
{
?>
  <tr>
    <td><?php echo lang_show('evdot');?></td>
    <td><input class="ltext" value="<?php if (isset($re['width'])) echo $re['width'];?>" name="width" type="text" id="width"> -- 
	<input class="rtext" name="height" type="text" id="height" value="<?php if (isset($re['height'])) echo $re['height']; ?>"></td>
  </tr>
<?php
}
?>
  <tr>
    <td><?php echo lang_show('time');?></td>
    <td>
    		<script language="javascript">
			var cdr = new Calendar("cdr");
			document.write(cdr);
			cdr.showMoreDay = true;
			</script>
    	<input class="ltext" readonly name="stime" type="text" id="stime" size="20" value="<?php if($re['stime']>0){echo date("Y-m-d",$re['stime']);}else echo date("Y-m-d");?>" onFocus="cdr.show(this);"> -- 
          <input class="rtext" readonly onFocus="cdr.show(this);" name="etime" type="text" id="etime" size="20" value="<?php
		  $date_list = array('day'=>1,'week'=>7,'month'=>30);
		  if(empty($re['etime']))
		  	$re['etime']=time()+3600*27*30;
		  echo date("Y-m-d",$re['etime']);?>">
       <span class="bz">(共<?php echo $re['show_time'].lang_show("$re[unit]");?> 参考时间:<?php  if($re['stime']>0){ echo date("Y-m-d",$re['stime']);}else{ echo date("Y-m-d");} echo '至'.date("Y-m-d",$re['etime']);?>)</span>
       </td>
  </tr>
  <tr>
    <td><?php echo lang_show('is_open');?></td>
    <td><input name="isopen" type="checkbox" class="checkbox" id="isopen" value="1" <?php if(!empty($re['isopen'])&&$re['isopen']==1) echo "checked=checked";?> /></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>" />
      <input class="btn" type="reset" name="Submit2" value="<?php echo lang_show('reset');?>" />
      <input name="submit" type="hidden" id="submit" value="submit" /></td>
  </tr>
</table>
  </div>
</div>
</form>
</body>
</html>