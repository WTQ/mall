<?php
include_once("../includes/global.php");
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//======================================
$step=isset($_POST["step"])?$_POST["step"]:NULL;
if($step=="new")
{
	$statu=$_POST["statu"]*1;
	$orderid=$_POST["orderid"]?$_POST["orderid"]:0;
	
	if(empty($_POST["stime"]))
		$_POST["stime"]=time();
	else
		$_POST["stime"]=strtotime($_POST["stime"]);
	if(empty($_POST["etime"]))
		$_POST["etime"]=time();
	else
		$_POST["etime"]=strtotime($_POST["etime"]);
	
	if($_POST["linkid"])
	{
		$sql="update ".LINK." set
	    name='$_POST[linkname]',url='$_POST[url]',statu='$statu',
		orderid='$orderid',log='$_POST[log]',stime='$_POST[stime]',etime='$_POST[etime]',con='$_POST[con]'
		where linkid=$_POST[linkid]";
		$db->query($sql);
	}
	else
	{
		$sql="insert into ".LINK." 
		(name,url,statu,orderid,log,stime,etime,con) 
		values  ('$_POST[linkname]','$_POST[url]','$statu','$orderid','$_POST[log]','$_POST[stime]','$_POST[etime]','$_POST[con]')";
		$db->query($sql);
	}
	msg("link.php");
}

if($_GET['linkid'])
{
	$db->query("select * from ".LINK." where linkid='$_GET[linkid]'");
	if($db->next_record()){
		$oldlinkid=$linkid;
		$stime=$db->f('stime');
		$etime=$db->f('etime');
		$oldlinkname=$db->f('name');
		$oldlinkid=$db->f('linkid');
		$oldurl=$db->f('url');
		$oldlogo=$db->f('log');
		$oldorderid=$db->f('orderid');
		$con=$db->f('con');
		$statu=$db->f('statu');
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo lang_show('admin_system');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
</HEAD>
<body>
  <form method="post" action="" enctype="multipart/form-data">
	<div class="bigbox">
	<div class="bigboxhead"> <?php echo lang_show('link_manager');?></div>
	<div class="bigboxbody">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="body_left" align="left" ><?php echo lang_show('link_name');?></td>
          <td width="85%"><input type="text" name="linkname" class="text" value="<?php echo isset($oldlinkname)?$oldlinkname:NULL;?>"></td>
        </tr>
        <tr>
          <td align="left" ><?php echo lang_show('link_url');?></td>
          <td><input type="text" name="url" class="text"value="<?php echo isset($oldurl)?$oldurl:'http://';?>"></td>
        </tr>
        <tr>
          <td align="left" >Logo</td>
          <td><input id="log" type="text" name="log" class="text" value="<?php echo isset($oldlogo)?$oldlogo:NULL;?>">
		 [<a href="javascript:uploadfile('上传LOGO','log',85,32,'')">上传</a>] 
		 [<a href="javascript:preview('log');">预览</a>]
		 [<a onclick="javascript:$('#log').val('');" href="#">删除</a>]</td>
        </tr>

                <tr>
                  <td align="left" >备注</td>
                  <td><textarea class="text" name="con" rows="5" id="con"><?php echo $con;?></textarea></td>
                </tr>
				        <tr>
          <td align="left" ><?php echo lang_show('VaildPeriod');?></td>
          <td>
		  	<script language="javascript">
			var cdr = new Calendar("cdr");
			document.write(cdr);
			cdr.showMoreDay = true;
			</script>
		  <input class="ltext" readonly name="stime" type="text" id="stime" value="<?php if(isset($stime)){echo date("Y-m-d",$stime);}?>" onFocus="cdr.show(this);"> -- 
          <input class="rtext" readonly onFocus="cdr.show(this);" name="etime" type="text" id="etime" value="<?php if(isset($etime)){echo date("Y-m-d",$etime);}?>">		  </td>
        </tr>
		<tr>
          <td align="left" ><?php echo lang_show('sort_sort');?></td>
          <td><input class="text" name="orderid" type="text" id="orderid" value="<?php echo isset($oldorderid)?$oldorderid:NULL;?>"></td>
        </tr>
		<tr>
          <td align="left" ><?php echo lang_show('statu');?></td>
          <td>
		  <select class="select" name="statu">
		    <option value="0"><?php echo lang_show('close');?></option>
			<option <?php if($statu==1) echo 'selected="selected"';?> value="1"><?php echo lang_show('open');?></option>
		    <option <?php if($statu==2) echo 'selected="selected"';?> value="2"><?php echo lang_show('recommend');?></option>
		  </select>
		  </td>
         
        </tr>
        <tr>
          <td align="left">&nbsp;</td>
          <td>
            <input type="hidden" name="step" value="new">
            <input type="hidden" name="linkid" value="<?php echo isset($oldlinkid)?$oldlinkid:NULL;?>">
            <input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit_link');?>"></td>
        </tr>
      </table>
	</div>
</div>
</form>
</body>
</html>