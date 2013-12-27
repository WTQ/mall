<?php
include_once("../includes/global.php"); 
include_once("../includes/page_utf_class.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//====================================
if(!empty($_GET['action'])&&$_GET["action"]=="del")
{
	if(isset($_GET['groupid'])&&$_GET['groupid']>3)
	  $deletegroupid=$_GET['groupid'];
	else
	  $deletegroupid=0;
	$sql="delete from ".USERGROUP." where group_id='".$deletegroupid."'"; 
	$db->query($sql);
	msg(" membergroup.php");
}
//=======================================
$group_array=array('groupid','groupname','groupstatu','groupdes','groupdes','grouplogo','groupid','groupfee','rebate');
if(!empty($_POST['submit'])&&$_POST["submit"]==lang_show('add')&&!empty($_POST['groupname']))
{
	$creattime=date("Y-m-d h:i:s");
	$groupn=$_POST['groupname'];
	$fee=$_POST['groupfee']*1;
	$groupd=$_POST['groupdes'];
	$groupl=$_POST['grouplogo'];
	$rebate=$_POST['rebate'];
	
    $sql="insert into ".USERGROUP." (name,des,logo,statu,creat_time,groupfee,con,rebate) 
	      values ('$groupn','$groupd','$groupl','1','$creattime','$fee','$_POST[con]','$rebate')";
	$db->query($sql);

	creat_file();
	msg('membergroup.php','发布成功');
}
if(!empty($_POST['submit']) && !empty($_POST['groupid']))
{
	$grpid=$_POST['groupid'];  
	$groupn=$_POST['groupname'];
	$groups=$_POST['groupstatu'];
	$fee=$_POST['groupfee']*1;
	$groupd=$_POST['groupdes'];
	$groupl=$_POST['grouplogo'];
	$rebate=$_POST['rebate'];
	$creattime=date("Y-m-d h:i:s");	
	if(!empty($groupn))
		$sql=",name='$groupn'";
	else
		$sql=NULL;
	$sql="update ".USERGROUP." set 
		des='$groupd',logo='$groupl',con='$_POST[con]',statu='$groups',creat_time='$creattime' ,groupfee='$fee',rebate='$rebate' $sql
    	where group_id='$grpid'";
	
	$db->query($sql);
	creat_file();
	msg('membergroup.php','更新成功');
}
//==================================
function creat_file()
{
	$write_group_data=get_user_group();//从库里取出数据生成数组
	$write_str=serialize($write_group_data);//将数组序列化后生成字符串
	$write_str='<?php $group = unserialize(\''.$write_str.'\');?>';//生成要写的内容
	$fp=fopen('../config/usergroup.php','w');
	fwrite($fp,$write_str,strlen($write_str));//将内容写入文件．
	fclose($fp);
}
function get_user_group()
{
	global $db;
	$sql="select group_id,name,des,logo,statu,creat_time,groupfee,rebate from ".USERGROUP;
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $key=>$v)
	{
		$gid=$v['group_id'];
		$sre[$gid]=$v;
	}
	return $sre;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<TITLE> <?php echo lang_show('admin_system');?></TITLE>
<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
</head>
<body>
<?php
if(empty($_GET['action'])&&$_GET['action']!="add")
{
?>
<div class="bigbox">
	<div class="bigboxhead tab">
		<span class="cbox on"><a href="membergroup.php"><?php echo lang_show('allmembergroup');?></a></span>
		<span class="cbox"><a href="?action=add"><?php echo lang_show('addgroup');?></a></span>
	</div>
    <div class="bigboxbody">
    <form name="groupuser" method="get" action="membergroup.php">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
	    <tr class="theader">
          <td height="31">名称</td>
          <td>编号</td>
          <td>状态</td>
          <td>图示</td>
          <td>积分满足点数</td>
          <td>折扣率</td>
          <td>描述</td>
          <td width="186">操作</td>
        </tr>
        <?php
        $sql="select * from ".USERGROUP." order by group_id asc";
        $db->query($sql);
        $re=$db->getRows();
        foreach($re as $v)
	    {
        ?>
        <tr>
          <td width="127" height="31"><?php echo $v['name'];?></td>
          <td width="64" height="31"><?php echo $v['group_id'];?></td>
          <td width="65"><?php if($v['statu']==1) echo lang_show('qiyong'); else echo lang_show('tingyong');?>&nbsp;</td>
          <td width="77"><?php echo "<img src='../".$v['logo']."' >";?>&nbsp;</td>
          <td width="88"><?php echo $v['groupfee'];?>&nbsp;</td>
          <td width="77"><?php echo $v['rebate'];?>%&nbsp;</td>
          <td><?php echo $v['des'];?>&nbsp;</td>
          <td><a href="membergroup.php?action=modify&groupid=<?php echo $v['group_id']; ?>"><?php echo $editimg;?></a>
		   <?php
			if($v['group_id']>3)
			{
		   ?>
			<a href="membergroup.php?action=del&groupid=<?php echo $v['group_id'] ;?>" onClick="javascript:return confirm('<?php echo lang_show('suredelugroup');?>')"><?php echo $delimg;?></a>
		   <?php
			}
		   ?>
		   &nbsp;</td>
        </tr>
        <?php
          }
         ?>
		 <tr>
          <td height="31" colspan="8" style="color:#666666">
		  1.会员等级默认包括3种会员类型。默认会员级级别不可以删除。
		  <br>
		  2.如果会员等级不够，可以自定以填加，但等级必须从低到高，编号越高系统会认为等级越高。自定义会员类型可以删除。	
		  </td>
        </tr>
      </table>
    </form>
    </div>
</div>
<?php
}
?>

<?php
if(!empty($_GET['action'])&&$_GET["action"]=="modify"&&empty($_POST['submit']))
{
   $groupid=$_GET['groupid'];
  $sql="select * from ".USERGROUP." where group_id='".$groupid."'";
  $db->query($sql);
  $rs=$db->fetchRow();
?>
<form method="post" action="membergroup.php">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('groupxiugai');?></div>
<div class="bigboxbody">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="theader">
    <td colspan="2"><?php echo lang_show('shuxing');?></td>
    </tr>
      <tr>
        <td height="40" ><?php echo lang_show('groupzt');?></td>
        <td width="858">
          <input type="radio" class="radio" name="groupstatu" value="1" <?php if($rs['statu']==1) echo "checked"; ?>>
          <?php echo lang_show('qiyong');?>
          <input type="radio" class="radio" name="groupstatu" value="0" <?php if($rs['statu']==0) echo "checked"; ?>>
          <?php echo lang_show('tingyong');?>		  </td>
        </tr>
      <tr>
        <td width="146" ><?php echo lang_show('groupname');?></td>
        <td>
          <input name="groupname" type="text" id="groupname" class="text" maxlength="30" value="<?php echo $rs['name']; ?>">		  </td>
        </tr>
      <tr>
        <td><?php echo lang_show('gpfee');?></td>
        <td><input name="groupfee" type="text" id="groupfee" class="text" value="<?php echo $rs['groupfee'];?>"></td>
      </tr>
      <tr>
        <td><?php echo lang_show('rebate');?></td>
        <td><input name="rebate" type="text" id="rebate" class="text" value="<?php echo $rs['rebate'];?>">%</td>
      </tr>
      <tr>
        <td><?php echo lang_show('grouplogo');?></td>
        <td>
          <input name="grouplogo" type="text" id="grouplogo" class="text" value="<?php echo $rs['logo']; ?>">
		 [<a href="javascript:uploadfile('上传LOGO','grouplogo',180,60,'')">上传</a>] 
		 [<a href="javascript:preview('grouplogo');">预览</a>]
		 [<a onclick="javascript:$('#grouplogo').val('');" href="#">删除</a>]		  </td>
        </tr>
	      <tr>
        <td><?php echo lang_show('groupdes');?></td>
        <td>
          <textarea name="groupdes" id="groupdes" class="text" style="height:70px;"><?php echo $rs['des']; ?></textarea><br />
		  <span class="bz">个别地方简要提示</span>
		  </td>
        </tr>
          <tr>
            <td height="256" align="left">详细描述</td>
            <td align="left">
			<script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
                            
			<script>
            var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name="con"]', {
                    resizeType : 1,
                    allowPreviewEmoticons : false,
                    allowImageUpload : false,
                    langType :'<?php echo $config['language']; ?>',
                });
            });
            </script>
            <textarea name="con" style="width:90%; height:400px;"><?php echo $rs["con"] ?></textarea>	

			<br /><span class="bz">在会员组介绍中详情展示，需要人工编辑，可以将您的会员政策等方面做一个详情描述，比如线上提供什么样的政策，线上有什么样的权限等。</span>
			</td>
          </tr>
        <tr>
  	<td align="center">&nbsp;</td>
    <td align="left">
    <input type="hidden" name="groupid" value="<?php echo $_GET['groupid']?>" />
    <input class="btn" type="submit" name="submit" value="<?php echo lang_show('submit');?>"/>&nbsp;&nbsp;
     <input class="btn" type="button" name="cancel" id="cancel" value="<?php echo lang_show('cancel');?>" onclick='javascript:window.location="membergroup.php"' />	 </td>
  </tr>
</table>
</div>
</div>
</form>
<?php } ?>






<?php
if(!empty($_GET['action'])&&$_GET["action"]=="add"&&empty($_POST['submit']))
{
?>
<form method="post" action="membergroup.php">
<div class="bigbox">
<div class="bigboxhead tab">
	<span class="cbox"><a href="membergroup.php"><?php echo lang_show('allmembergroup');?></a></span>
	<span class="cbox on"><a href="?action=add"><?php echo lang_show('addgroup');?></a></span>
</div>
<div class="bigboxbody">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td width="135"><?php echo lang_show('groupname');?></td>
	<td><input name="groupname" type="text" id="groupname" class="text" ></td>
	</tr>
  <tr>
	<td><?php echo lang_show('gpfee');?></td>
	<td><input name="groupfee" type="text" id="groupfee" class="text" ></td>
  </tr>
  <tr>
    <td><?php echo lang_show('rebate');?></td>
    <td><input name="rebate" type="text" id="rebate" class="text">%</td>
  </tr>
  <tr>
	<td><?php echo lang_show('groupdes');?></td>
	<td><textarea name="groupdes" id="groupdes" class="text" style="height:70px;"></textarea></td>
	</tr>
  <tr>
	<td><?php echo lang_show('grouplogo');?></td>
	<td><input name="grouplogo" type="text" id="grouplogo" class="text"  /></td>
	</tr>
  
  <tr>
	<td>&nbsp;</td>
	<td>
	<input class="btn" type="submit" name="submit" value="<?php echo lang_show('addtype');?>"/>
	  &nbsp;&nbsp;
	  <input class="btn" type="button" name="cancel2" id="cancel2" value="<?php echo lang_show('cancel');?>" onClick='javascript:window.location="membergroup.php"' /></td>
  </tr>
</table>
</div>
</div>
</form>
<?php
}
?>
</body>
</html>