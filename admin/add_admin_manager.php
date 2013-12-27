<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
@session_start();
//============================================
if(!empty($_POST['user'])&&!isset($_GET['id']))
{
	foreach($_POST as $key=>$v)
	{
		$_POST[$key]=trim($_POST[$key]);
	}
	$sql="select * from ".ADMIN." WHERE user='$_POST[user]'";
	$db->query($sql);
	if(!$db->num_rows())
	{
		$_POST['password']=md5($_POST['password']);
		
		$sql="select name from ".DISTRICT." WHERE id='$_POST[province]'";
		$db->query($sql);
		$_POST['province']=$db->fetchField('name');
		
		$sql="select name from ".DISTRICT." WHERE id='$_POST[city]'";
		$db->query($sql);
		$_POST['city']=$db->fetchField('name');
		
		$sql="select name from ".DISTRICT." WHERE id='$_POST[area]'";
		$db->query($sql);
		$_POST['area']=$db->fetchField('name');
	
		$sql="insert into ".ADMIN."
		 (user,name,password,group_id,`desc`,province,city,area,lang)
		  VALUES
		 ('$_POST[user]','$_POST[name]','$_POST[password]','$_POST[group_id]','$_POST[desc]','$_POST[province]','$_POST[city]','$_POST[area]','$_POST[lang]')";
		 $re=$db->query($sql);
		 if($re)
			msg("admin_manager.php");
	}
	else
		msg("add_admin_manager.php?type=1");
}
if(!empty($_POST['group_id'])&&!empty($_GET['id']))
{
	if(!empty($_POST['password']))
	{
		$_POST['password']=md5($_POST['password']);
		$pa="password='$_POST[password]',";
	}
	
	$sql="select name from ".DISTRICT." WHERE id='$_POST[province]'";
	$db->query($sql);
	$_POST['province']=$db->fetchField('name');
	
	$sql="select name from ".DISTRICT." WHERE id='$_POST[city]'";
	$db->query($sql);
	$_POST['city']=$db->fetchField('name');
		
	$sql="select name from ".DISTRICT." WHERE id='$_POST[area]'";
	$db->query($sql);
	$_POST['area']=$db->fetchField('name');
	
	$sql="update ".ADMIN." set ".$pa." group_id='".$_POST['group_id']."', `desc`='".$_POST['desc']."', province='$_POST[province]', city='$_POST[city]', area='$_POST[area]',lang='$_POST[lang]',name='$_POST[name]' where id='".$_GET['id']."'";
	$re=$db->query($sql);
	 if($re)
		msg("admin_manager.php");
}

//---------是否有会员组存在，不存在就转向增加会员组功能。
if($_SESSION['province'])
	$tsql=" and province='$_SESSION[province]'";
if($_SESSION['city'])
	$tsql.=" and city='$_SESSION[city]'";
if($_SESSION['area'])
	$tsql.=" and area='$_SESSION[area]'";
	
$sql="select * from ".GROUP." where 1=1 order by group_id desc";
$db->query($sql);
$havegroup=$db->getRows();
if(count($havegroup)==0)
	msg('group.php');
//-----------------------------------------------
if(!empty($_GET['id']))
{
	$sql="select * from ".ADMIN." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
$sql="SELECT a.id, a.user, a.group_id,g.group_name FROM ".ADMIN." a
	LEFT JOIN ".GROUP." g ON a.group_id = g.group_id
	WHERE a.user <> 'admin'";
$db->query($sql);
$users = $db->getRows();

function read_dir($dir)
{
	$i=0;
	$handle = opendir($dir); 
	$rdir=array();
	while ($filename = readdir($handle))
	{ 
		if($filename!="."&&$filename!=".."&&$filename!=".svn")
		{
		  if(is_dir($dir.$filename))
		  { 
		  	 if(substr($filename,0,5)!='user_'&&substr($filename,0,8)!='special_'&&substr($filename,0,5)!='email')
		   	 	$rdir[]=$filename;
		  }
	   }
	}
	return $rdir;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../script/district.js" ></script>
<script>
var weburl="<?php echo $config["weburl"]; ?>";
</script>
<style>.hidden{ display:none;}</style>
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('add_manager');?></div>
	<div class="bigboxbody">
  <form name="form1" method="post" action="">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <?php
	 if(!empty($_GET['type'])&&$_GET['type']==1)
	 {p
	 ?>
	 <tr>
        <td>&nbsp;</td>
        <td><?php echo lang_show('repeat_msg');?></td>
      </tr>
	  <?php }?>
      <tr>
        <td  class="body_left"><?php echo lang_show('actuser');?>&nbsp;</td>
        <td><input class="text" type="text" name="user" value="<?php echo $de['user'];?>" <?php if($de['user']){echo "disabled";}?> /></td>
      </tr>
      <tr>
        <td><?php echo lang_show('password');?></td>
        <td><input class="text" type="text" name="password"><?php if(!empty($_GET['id'])){echo lang_show('passmsg');}?></td>
      </tr>
	  <tr>
        <td  class="body_left"><?php echo lang_show('manager_name');?></td>
        <td width="85%"><input class="text" type="text" name="name" value="<?php echo $de['name'];?>" /></td>
      </tr>
      <tr>
        <td><?php echo lang_show('belonggroup');?></td>
        <td>
          <select class="select" name="group_id">
		  <?php
		  foreach($havegroup as $v)
		  {
		  	if($v['group_id']==$de['group_id'])
				echo "<option value='".$v['group_id']."' selected >".$v['group_name']."</option>";
			else
		  		echo "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
		  }
		  ?>
          </select>        </td>
      </tr>
      	<tr>
		<td width="10%"><?php echo lang_show('becity');?></td>
		<td>
        
<input type="hidden" name="province" id="id_1" value="<?php echo getdistrictid($de["province"]); ?>" /> 
<input type="hidden" name="city" id="id_2" value="<?php echo getdistrictid($de["city"]); ?>" />
<input type="hidden" name="area" id="id_3" value="<?php echo getdistrictid($de["area"]); ?>" />

     
     <?php if($de["province"]){ ?>
        <div id="d_1"><?php echo $de["province"].$de["city"].$de["area"]; ?>&nbsp;&nbsp;<a href="javascript:sd();">编辑</a></div>
        <?php } ?>
     
     
     	<div id="d_2" <?php if($de["province"]){ ?>class="hidden" <?php } ?>>
     
        <select id="select_1" onChange="selClass(this);">
        <option value="">--请选择--</option>
		<?php echo GetDistrict(); ?>
        </select>
        
        <select id="select_2" onChange="selClass(this);" class="hidden"></select>
        <select id="select_3" onChange="selClass(this);" class="hidden"></select>
        
        </div>
          
        </td>
	</tr>
	<tr>
		<td width="10%"><?php echo lang_show('admin_language');?></td>
		<td>
			<select class="select" name='lang'>
			<?php
				  $dir=read_dir('../lang/');
				  foreach($dir as $v)
				  {
					if($v==$de['lang'])
						$sl="selected";
					else
						$sl=NULL;
					echo "<option value='$v' $sl>$v</option>";
				  }
			  ?>
			</select>		</td>
	</tr>
      <tr>
        <td><?php echo lang_show('des');?></td>
        <td>
        <textarea class="text" name="desc" cols="50" rows="7"><?php if(!empty($de['desc'])) echo $de['desc'];?></textarea>        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>"></td>
      </tr>
    </table>
    </form>
</div>
</div>
</body>
</html>