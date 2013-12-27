<?php
/*
 *Coupright B2Bbuilder
 *Powered by http://www.b2b-builder.com
 *Auther:Brad zhang
 *Des: company cat
 *Date:2008-11-14
 */
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//=======================================
$add_type=empty($_GET['add_type'])?1:$_GET['add_type'];
if(!empty($_POST["Submit"])&&empty($_GET["edit"])&&$add_type==2)
{
	$p=$_POST;
	include('../lib/allchar.php');
	
	if(!empty($p['province']) and empty($p['city']))
	{
		$sql="select name from ".DISTRICT." WHERE id='$p[province]'";
		$db->query($sql);
		$p['provinces']=$db->fetchField('name');
	
		$sql="select name from ".DISTRICT."  WHERE pid='$p[province]'";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			$v['web_title']=str_replace('keyword',$v['name'],$p['web_title']);
			$v['web_keyword']=str_replace('keyword',$v['name'],$p['web_keyword']);
			$v['web_des']=str_replace('keyword',$v['name'],$p['web_des']);
			$v['copyright']=str_replace('keyword',$v['name'],$p['copyright']);
			$v['des']=str_replace('keyword',$v['name'],$p['des']);
			$domain=c($v['name']);
			
			$sql="insert into ".DOMAIN." 
			(`dtype`,`domain`,`con`,`con2`,`con3`,`des`,web_title,web_keyword,web_des,copyright,template) 
			values 
			('1','$domain','$p[provinces]','$v[name]','','$v[des]','$v[web_title]','$v[web_keyword]','$v[web_des]','$v[copyright]','$p[template]')";
			$re=$db->query($sql);
		}
	}
	if(!empty($p['province']) and !empty($p['city']))
	{
		$sql="select name from ".DISTRICT." WHERE id='$p[province]'";
		$db->query($sql);
		$p['provinces']=$db->fetchField('name');
		
		$sql="select name from ".DISTRICT." WHERE id='$p[city]'";
		$db->query($sql);
		$p['citys']=$db->fetchField('name');
	
		$sql="select name from ".DISTRICT."  WHERE pid='$p[city]'";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
			$v['web_title']=str_replace('keyword',$v['name'],$p['web_title']);
			$v['web_keyword']=str_replace('keyword',$v['name'],$p['web_keyword']);
			$v['web_des']=str_replace('keyword',$v['name'],$p['web_des']);
			$v['copyright']=str_replace('keyword',$v['name'],$p['copyright']);
			$v['des']=str_replace('keyword',$v['name'],$p['des']);
			$domain=c($v['name']);
			
			$sql="insert into ".DOMAIN." 
			(`dtype`,`domain`,`con`,`con2`,`con3`,`des`,web_title,web_keyword,web_des,copyright,template) 
			values 
			('1','$domain','$p[provinces]','$p[citys]','$v[name]','$v[des]','$v[web_title]','$v[web_keyword]','$v[web_des]','$v[copyright]','$p[template]')";
			$re=$db->query($sql);
		}
	}
	if($re)
		msg("sub_domain_list.php");
}
if(!empty($_POST["Submit"])&&empty($_GET["edit"])&&$add_type==1)
{
	$p=$_POST;
	$sql="select name from ".DISTRICT." WHERE id='$p[province]'";
	$db->query($sql);
	$p['province']=$db->fetchField('name');
	
	$sql="select name from ".DISTRICT." WHERE id='$p[city]'";
	$db->query($sql);
	$p['city']=$db->fetchField('name');
	
	$sql="select name from ".DISTRICT." WHERE id='$p[area]'";
	$db->query($sql);
	$p['area']=$db->fetchField('name');
	
	$sql="insert into ".DOMAIN." 
	(`dtype`,`domain`,`con`,`con2`,`con3`,`des`,logo,web_title,web_keyword,web_des,copyright,template) 
	values 
	('1','$p[domain]','$p[province]','$p[city]','$p[area]','$p[des]','$p[logo]','$p[web_title]','$p[web_keyword]','$p[web_des]','$p[copyright]','$p[template]')";

	$re=$db->query($sql);
	if($re)
		msg("sub_domain_list.php");
}
if(!empty($_POST["Submit"])&&!empty($_GET["edit"]))
{
	$p=$_POST;
	$p["isopen"]=$p["isopen"]?1:0;
	
	$sql="select name from ".DISTRICT." WHERE id='$p[province]'";
	$db->query($sql);
	$p['province']=$db->fetchField('name');
	
	$sql="select name from ".DISTRICT." WHERE id='$p[city]'";
	$db->query($sql);
	$p['city']=$db->fetchField('name');
	
	$sql="select name from ".DISTRICT." WHERE id='$p[area]'";
	$db->query($sql);
	$p['area']=$db->fetchField('name');
	
	$sql="update ".DOMAIN." set `domain`='$p[domain]',`con`='$p[province]',`con2`='$p[city]',`con3`='$p[area]',`des`='$p[des]',`isopen`='$p[isopen]',web_title='$p[web_title]',web_keyword='$p[web_keyword]',web_des='$p[web_des]',copyright='$p[copyright]',template='$p[template]',logo='$p[logo]' where id='$_GET[edit]'";
	$re=$db->query($sql);
	if($re)
		msg("sub_domain_list.php");
}
if(!empty($_GET["edit"]))
{
	$sql="select * from ".DOMAIN." where id='$_GET[edit]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
//=========================================
function read_dir($dir)
{
	$i=0;
	$handle = opendir($dir); 
	$rdir=array();
	while ($filename = readdir($handle))
	{ 
		if($filename!="."&&$filename!="..")
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
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
<script src="../script/my_lightbox.js" language="javascript"></script>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../script/district.js" ></script>
<script>
var weburl="<?php echo $config["weburl"]; ?>";
</script>
<style>
.hidden { display: none;}
</style>
<div class="bigbox">
	<div class="bigboxhead tab" style=" width:90%">
		<span class="cbox"><a href="sub_domain_list.php">管理分站</a></span>
		<span class="cbox on"><a href=""><?php echo lang_show('add_type');?></a></span>
	</div>

	<div class="bigboxbody" style="margin-top:-1px;">
<form method="post" action="">
<?php if(!empty($config['baseurl'])){?>
<table width="100%" align="center" cellpadding="4" cellspacing="0" >
  <?php if(empty($_GET['editid'])){ ?>
  <tr>
    <td width="13%" align="right"  class="body_left">&nbsp;</td>
    <td width="26%">
	<label><input onClick="window.location='add_sub_domain.php?add_type=1';" checked="checked" type="radio" class="radio" name="add_type" value="1">单个填加</label>　
    <label><input onClick="window.location='add_sub_domain.php?add_type=2';" <?php if($_GET['add_type']==2) echo 'checked="checked"';?> type="radio" class="radio" name="add_type" value="2">批量生成</label>	</td>
    <td width="61%">&nbsp;</td>
  </tr>
  <?php } ?>
  <tr>
      <td class="body_left" align="left" ><?php echo lang_show('prov');?></td>
      <td width="26%" >
<input type="hidden" name="province" id="id_1" value="<?php echo getdistrictid($de["con"]); ?>" /> 
<input type="hidden" name="city" id="id_2" value="<?php echo getdistrictid($de["con2"]); ?>" />
<?php if($_GET['add_type']!=2){ ?>
<input type="hidden" name="area" id="id_3" value="<?php echo getdistrictid($de["con3"]); ?>" />
<?php }?>
     
     <?php if($de["con"]){ ?>
        <div id="d_1"><?php echo $de["con"].$de["con2"].$de["con3"]; ?>&nbsp;&nbsp;<a href="javascript:sd();">编辑</a></div>
        <?php } ?>
     
     
     	<div id="d_2" <?php if($de["con"]){ ?>class="hidden" <?php } ?>>
     
        <select id="select_1" onChange="selClass(this);">
        <option value="">--请选择--</option>
		<?php echo GetDistrict(); ?>
        </select>
        <select id="select_2" onChange="selClass(this);" class="hidden"></select>
        
        <?php if($_GET['add_type']!=2){ ?>
        <select id="select_3" onChange="selClass(this);" class="hidden"></select>
        <?php }?>
        
        </div>
      </td>
      <td width="61%"></td>
  </tr>
  
    <tr>
      <td align="left" ><?php echo lang_show('temp');?></td>
      <td>
      <select name="template" id="template" class="select">
          <?php
		  $dir=read_dir('../templates/');
		  foreach($dir as $v)
		  {
		  	if(substr($v,0,1)!='.')
			{
				if($v==$de['template'])
					$sl="selected";
				else
					$sl=NULL;
				echo "<option value='$v' $sl>$v</option>";
			}
		  }
		  ?></select>		  </td>
      <td>&nbsp;</td>
    </tr>
	<?php if($_GET['add_type']!=2){ ?>
      <tr>
    <td align="left"  ><?php echo lang_show('sub_domain');?></td>
    <td>
      http://<input value="<?php echo $de['domain'];?>" name="domain" type="text" id="domain" size="10" maxlength="10">.<?php echo $config['baseurl'];?></td>
    <td><span class="bz"><?php echo lang_show('domain_des');?></span></td>
      </tr>
	 
      <tr>
        <td align="left">LOGO</td>
        <td><input class="text" value="<?php echo $de['logo'];?>" id="logo" name="logo" type="text"></td>
        <td style="color:#666666">
		 [<a href="javascript:uploadfile('上传LOGO','logo',180,60,'')">上传</a>] 
		 [<a href="javascript:preview('logo');">预览</a>]
		 [<a onclick="javascript:$('#logo').val('');" href="#">删除</a>]</td>
      </tr> 
	  <?php } ?>
      <tr>
      <td align="left"><?php echo lang_show('substationtitle');?></td>
      <td><input class="text" value="<?php echo $de['web_title'];?>" type="text" name="web_title" id="web_title">     </td>
      <td class="bz"><?php if($_GET['add_type']==2){?>
        省份或城市用keyword替代，如：keyword商机网，批量生成时会用城市名替换keyword。        <?php } ?></td>
    </tr>
    <tr>
      <td align="left" ><?php echo lang_show('substationkey');?></td>
      <td><input value="<?php echo $de['web_keyword'];?>" class="text" type="text" name="web_keyword" id="web_keyword"></td>
      <td class="bz"><?php if($_GET['add_type']==2){?>
        省份或城市用keyword替代，如：keyword商机网，批量生成时会用城市名替换keyword。
          <?php } ?></td>
    </tr>
    <tr>
      <td align="left" ><?php echo lang_show('substationdes');?></td>
      <td><input value="<?php echo $de['web_des'];?>" class="text" type="text" name="web_des" id="web_des"></td>
      <td class="bz"><?php if($_GET['add_type']==2){?>
        省份或城市用keyword替代，如：keyword商机网，批量生成时会用城市名替换keyword。
          <?php } ?></td>
    </tr>
      <tr>
    <td align="left" ><?php echo lang_show('des');?></td>
    <td class="bz" colspan="2" >
      <textarea name="des" class="text" style="height:100px;"><?php echo $de['des'];?></textarea>
      <?php if($_GET['add_type']==2){?>
      省份或城市用keyword替代，如：keyword商机网，批量生成时会用城市名替换keyword。
      <?php } ?></td>
  </tr>
      <tr>
        <td align="left" >Copyright</td>
        <td class="bz" colspan="2" ><textarea name="copyright" class="text" style="height:100px;"><?php echo $de['copyright'];?></textarea>
          <?php if($_GET['add_type']==2){?>
          省份或城市用keyword替代，如：keyword商机网，批量生成时会用城市名替换keyword。
          <?php } ?></td>
      </tr>
  <?php if(!empty($_GET['edit'])){ ?>
   <tr>
    <td align="left" ><?php echo lang_show('is_open');?></td>
    <td colspan="2"><input name="isopen" type="checkbox" class="checkbox" id="isopen" value="1" <?php if($de['isopen'])echo "checked";?>></td>
  </tr>
  <?php } ?>
  <tr>
    <td align="left">&nbsp;</td>
    <td colspan="2" >
      <input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>">   </td>
  </tr>
</table>
</form>
<?php
}
else
{
	admin_msg('system_config.php','系统设置中“网站基本路径”没有填写，请先设置网站基本路径！');
}
?>
</div>
</div>

</BODY>
</HTML>
