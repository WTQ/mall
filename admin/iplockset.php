<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================
if(!empty($_POST['addlockip'])||!empty($_GET['ip']))
{
	$locktime=0;$maddip=NULL;$autore=0;$otime=time();
	if(!empty($_POST['addlockip']))
		$maddip=$_POST['addlockip'];
	if(!empty($_GET['ip']))
		$maddip=$_GET['ip'];
		
	if(!empty($_POST['locktime']))
		$locktime=strtotime($_POST['locktime']);
	else
		$locktime=time()+3600*24*365;
		
	$type=empty($_POST['type'])?1:$_POST['type'];
	
	$sql="select id from ".IPLOCK." where ip='$maddip'";
	$db->query($sql);
	if(!$db->fetchField('id'))
	{
		$sql="insert into ".IPLOCK." 
		(ip,reason,optime,stoptime,autorelease,statu,type)
		VALUES
		('$maddip','".$_SESSION["ADMIN_USER"]."','$otime','$locktime','1','1','$type')"; 
		$db->query($sql);
	}
	getipdata();
	msg('iplockset.php');
}
//-------------------
if(!empty($_GET['action'])&&$_GET['action']=="release"&&!empty($_GET['lockid']))
{
	$sql="update ".IPLOCK." set statu=0,stoptime='".time()."',optime='".time()."' where id=".$_GET['lockid']; 
	$db->query($sql);
	getipdata();
}
if(!empty($_GET['action'])&&$_GET['action']=="lock"&&!empty($_GET['lockid']))
{
	$sql="update ".IPLOCK." set statu=1,stoptime='".(time()+3600*24*365)."',optime='".time()."' where id=".$_GET['lockid']; 
	$db->query($sql);
	getipdata();
}
//---------------------
if(!empty($_GET['releaseall'])&&empty($_GET['action']))
{
	if(is_array($_GET['checkip']))
	{
		$delid=implode(",",$_GET['checkip']);
		$sql="update ".IPLOCK." set statu=0,stoptime='".time()."',optime='".time()."' where id in ($delid)";
		$db->query($sql);
	}
	getipdata();
}
if(!empty($_GET['lockall'])&&empty($_GET['action']))
{
	if(is_array($_GET['checkip']))
	{
		$delid=implode(",",$_GET['checkip']);
		$sql="update ".IPLOCK." set statu=1,stoptime='".(time()+3600*24*365)."',optime='".time()."' where id in ($delid)";
		$db->query($sql);
	}
	getipdata();
}
if(!empty($_GET['delrecord'])&&empty($_GET['action']))
{
	if(is_array($_GET['checkip']))
	{
		$delid=implode(",",$_GET['checkip']);
		$sql="delete from ".IPLOCK." where id in ($delid)";
		$db->query($sql);
	}
	getipdata();
}
//-------------------------------------------------------
function getipdata()
{
	global $db;
	$sre=array();
	$sql="select ip,type from ".IPLOCK." where statu=1";
	$db->query($sql);
	$re=$db->getRows();
	foreach ($re as $v)
	{
		if($v['type']==1)
			$stop_view[]=$v['ip'];
		else
			$stop_reg[]=$v['ip'];
	}
	$stop_view=serialize($stop_view);
	$stop_reg=serialize($stop_reg);
	
	$write_str='<?php $stop_view = unserialize(\''.$stop_view.'\');$stop_reg=unserialize(\''.$stop_reg.'\');?>';//生成要写的内容
	$fp=fopen('../config/stop_ip.php','w');
	fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
	fclose($fp);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script language="javascript" src="../script/Calendar.js"></script>
<script type="text/javascript">
function checkall()
{
	 for(var j = 0 ; j < document.getElementsByName("checkip[]").length; j++)
	 {
	  	if(document.getElementsByName("checkip[]")[j].checked==true)
	  	  document.getElementsByName("checkip[]")[j].checked = false;
		else
		  document.getElementsByName("checkip[]")[j].checked = true;
	 }
}
</script>
<div class="bigbox" <?php if($_GET['act']=='add') echo 'style="display:block;"';else echo 'style="display:none;"';?> >
	<div class="bigboxhead tab" style=" width:90%">
		<span class="cbox"><a href="iplockset.php">已增加IP</a></span>
		<span class="cbox on"><a href="">增加IP</a></span>
	</div>
	<div class="bigboxbody" style="margin-top:-1px;">
	
	<form name="iplockset" action="iplockset.php" method="post" style="margin-top:0px;">
	<table width="99%" height="154" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td  class="searh_left" align="left" >
			<?php echo lang_show('ipaddr');?>
			<script language="javascript">
			var cdr = new Calendar("cdr");
			document.write(cdr);
			cdr.showMoreDay = true;
			</script>
			</td>
			<td align="left" ><input name="addlockip" type="text" id="addlockip" class="text" maxlength="30"></td>
        </tr>
        <tr>
          <td  align="left" >解锁时间</td>
		  <td   align="left" ><input onFocus="cdr.show(this);" name="locktime" type="text" id="locktime" class="text" maxlength="10"></td>
        </tr>
        <tr>
          <td height="37"   align="left" >锁定类型</td>
		  <td   align="left" >
		  <select class="select" name="type" id="type">
		    <option value="1" selected>禁止访问</option>
		    <option value="2">禁止注册</option>
          </select>
		  </td>
        </tr>
        <tr>
          <td   align="left" >&nbsp;</td>
          <td   align="left" ><input class="btn" type="submit" name="Input" id="addip" value="提交"></td>
        </tr>
	</table>
	</form>
	</div>
	</div>
<div class="bigbox" <?php if($_GET['act']=='') echo 'style="display:block;"';else echo 'style="display:none;"';?> >
	<div class="bigboxhead tab" style=" width:90%">
		<span class="cbox on"><a href="#">已增加IP</a></span>
		<span class="cbox"><a href="iplockset.php?act=add">增加IP</a></span>
	</div>
	<div class="bigboxbody" style="margin-top:-1px;">
	<form name="iplockset" action="" method="GET">
	<table width="100%" height="132" border="0" cellpadding="0" cellspacing="0">
	<?php
	unset($sql);
	if(!empty($_GET['sip']))
	{
		$_GET['sip']=trim($_GET['sip']);
		$sql=" and ip='$_GET[sip]' ";
	}
	if(!empty($_GET['type']))
	{
		$sql=" and type='$_GET[type]' ";
	}
	$sql="select * from ".IPLOCK." where 1 $sql order by id desc ";
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows'))
	{
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$pages = $page->prompt();   
	$db->query($sql);
	$re=$db->getRows();
	?>
        <tr>
          <td colspan="8" align="left">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td style="border:none" class="searh_left">IP</td>
              <td style="border:none"><input class="text" name="sip" type="text" value="<?php echo $_POST['sip'];?>" id="sip" /></td>
            </tr>
            <tr>
              <td style="border:none">锁定类型</td>
              <td style="border:none"><select class="select" name="select" id="select">
                <option value="">全部</option>
                <option value="1" <?php if($_GET['type']==1) echo 'selected';?>>禁止访问</option>
                <option value="2" <?php if($_GET['type']==2) echo 'selected';?>>禁止注册</option>
              </select></td>
            </tr>
            <tr>
              <td style="border:none">&nbsp;</td>
              <td style="border:none"><input name="Submit" type="submit" class="btn" value="查找" /></td>
            </tr>
          </table></td>
        </tr>
        
        <tr class="theader"fl>
          <td align="left" >
          <input type="checkbox" class="checkbox" name="checkipall" id="checkipall" onClick="checkall()"></td>
          <td align="left" ><?php echo lang_show('lockedip');?></td>
          <td align="left" ><?php echo lang_show('statu');?></td>
          <td align="left" >解锁时间</td>
          <td align="left" >锁定类型</td>
          <td align="left" ><?php echo lang_show('optime');?></td>
          <td align="left" >操作员</td>
          <td  align="left" ><?php echo lang_show('options');?></td>
        </tr>
        <?php
           foreach($re as $v)
	       {
        ?>
        <tr>
          <td width="53" align="left"><input type="checkbox" class="checkbox" name="checkip[]" value="<?php echo $v['id'];?>"></td>
          <td width="284" align="left">
		  <?php 
			echo $v['ip']; 
			if($v['ip'])
			{	
				echo '['.convertip($v['ip'], '../lib/tinyipdata.dat').']';
			}
		  ?>		  </td>
          <td width="112" align="left">
		  <?php 
			  if($v['statu']==1) 
				  echo "<font color='#FF3300'>".lang_show('locking')."</font>";
			  else
				  echo lang_show('unlock');
		  ?>		  </td>
          <td width="112" align="left" ><?php echo dateformat($v['stoptime']);?></td>
          <td width="99" align="left" ><?php 
			  if($v['type']==1) 
				  echo "禁止访问";
			  else
				  echo "禁止注册";
		  ?></td>
          <td width="115" align="left" ><?php echo dateformat($v['optime']);?></td>
          <td width="112" align="left" ><?php echo $v['reason'];?></td>
          <td width="143" align="left" ><?php
		  if ($v['statu']==1)
		  {
		  ?>
		  <a href="iplockset.php?action=release&lockid=<?php echo $v['id'];?>"><?php echo lang_show('olock');?></a>
          <?php
		  }
		  else
		  {
		  ?>
           <a href="iplockset.php?action=lock&lockid=<?php echo $v['id'];?>"><?php echo lang_show('lockit');?></a>
        <?php
		  }
          }
        ?>		  </td>
       </tr>
        <tr>
          <td colspan="5" align="left" style="text-align:left">
		  <input class="btn" type="submit" name="delrecord" id="delrecord" value="删除">
		  <input class="btn" type="submit" name="releaseall" value="解除锁定" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');">
            <input name="lockall" type="submit" class="btn" id="alllock" value="锁定"></td>
          <td  colspan="3" align="right">&nbsp;<div class="page"><?php echo $pages?></div></td>
        </tr>
      </table>
	</form>
	</div>
</div>
</body>
</html>