<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
include_once("../includes/page_utf_class.php");
//==========================================
if(!empty($_POST["action"])&&$_POST["action"]==lang_show('delete'))
{
	if(isset($_POST["de"]) && is_array($_POST["de"]))
	{
		$id=implode(",",$_POST["de"]);
		if($id)
			$db->query("update ".ADVSCON." set statu='-2' where id in ($id) and statu=-1");
	}
}

elseif('audit'==$_GET["submit"]||!empty($_POST['audit']) )
{
	if(isset($_GET['submit'])&&isset($_GET['id'])&&isset($_GET['audit']))
	{
		$sql="update ".ADVSCON." set statu='$_GET[audit]' where ID='$_GET[id]'";
		$db->query($sql);
		msg(urldecode($_GET['fw']));
	}
}

$db->query("select * from ".ADVS." order by ID asc");
$adv_groups = array();
while($pl=$db->fetchRow())
	$adv_groups[$pl['ID']] = $pl;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="main.js"></script>
<script src="../script/my_lightbox.js" language="javascript"></script>
<script>
function chk_select(name)
{
	 var box_l = document.getElementsByName(name).length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName(name)[j].checked==true)
			return true;
	 }
	 alert("<?php echo lang_show('select_chk');?>");
	 return false;
}
</script>
</HEAD>
<body>

  <div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('adv_audit');?></div>
	<div class="bigboxbody">
	<form method="get"  action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
       <td><?php echo lang_show('statu');?></td>
       <td>
       <?php
          $status=array(
              '4'=>lang_show('all'),
			  '0'=>'未支付',
              '1'=>'已支付',
          );
      ?> 
        <select class="select" name="statu">
        <?php
        foreach($status as $key=>$v)
        {
        ?>
          <option value="<?php echo $key;?>" <?php if(isset($_GET['statu'])&&$_GET['statu']==$key)echo "selected";?>>
            <?php echo $v;?>
          </option>
        <?php
         } 
        ?>
        </select>
       </td>
     </tr>
    <tr>
      <td>选项</td>
      <td><select class="select" name="gid">
		<option value=''><?php echo lang_show('all'); ?></option>
	    <?php
		foreach($adv_groups as $key=>$v)
		{
		?>
			<option value="<?php echo $key;?>" <?php if(!empty($_GET['gid'])&&$_GET['gid']==$key)echo "selected";?>> <?php echo $v['name'];?> </option>
		<?php
		 }
		 ?>
        </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	    <input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>">
       </td>
    </tr>
</table>
</form>

<form action="" name='frm' method="POST" onsubmit="return chk_select('de[]')">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
		<tr class="theader"> 
		  <td width="25"><input onClick="do_select()" type="checkbox" class="checkbox" name="checkbox" value="checkbox"></td>
		  <td><?php echo lang_show('adv_name');?></td>
		  <td width="80"><?php echo lang_show('guser');?></td>
		  <td width="130" align="left"  ><?php echo lang_show('time');?></td>
		  <td width="70" align="left"  ><?php echo lang_show('adv_showtime');?></td>
		  <td width="70" align="left" ><?php echo lang_show('statu');?></td>
		</tr>
		<?php
		if(!empty($_GET["gid"]))
			$scl.=" AND a.group_id='$_GET[gid]' ";
	
		if(isset($_GET["statu"])&&$_GET["statu"]<4)
			$scl.=$_GET["statu"]==-2?" AND  a.etime>0 and a.etime<'".time()."'":" AND a.statu=".intval($_GET['statu']);
		else
			$scl.=" AND a.statu>-2 ";
			
		$sql="select a.*,u.user	 from ".ADVSCON."  a left join ".ALLUSER."  u on a.userid=u.userid  where a.userid>0 $scl order by a.ID desc ";
		//=============================
		$page = new Page;
		$page->listRows=20;
		if (!$page->__get('totalRows')){
			$db->query("select count(*) as total	from ".ADVSCON." a  where a.userid>0 $scl");
			$page->totalRows = $db->fetchField('num');
		}
		$sql .= "  limit ".$page->firstRow.",20";
		$pages = $page->prompt();
		//=====================
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
		?> 
      <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')"> 
         <td width="25"><input name="de[]" type="checkbox" class="checkbox" id="de"  <?php if($v['etime']>0&&$v['etime']<time()) echo "disabled='disabled'";?> value="<?php echo $v['ID']; ?>"></td>
         <td><?php echo $v['name'];?></td>
		 <td><?php echo $v['user'];?></td>
		 <td><?php echo date('Y-m-d H:i:s',$v['ctime']);?></td>
         <td><?php echo $v['show_time'].lang_show($v['unit']); ?>&nbsp;</td>
		 <td><?php  echo $status[$v['statu']];?></td>
      </tr>
    <?php 
    }
	?> 
  </table>
  <table width="100%" height="20" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="45%">
	  <input class="btn"  type="submit" name="action" type='submit' value='<?php echo lang_show('delete');?>' />
	  <input type='hidden' value='' name='audit' id='audit' />
	  </td>
      <td width="65%" align="right"><div class="page"><?php echo $pages?></div></td>
    </tr>
  </table>
  </form>
  </div>
</div>
</body>
</html>