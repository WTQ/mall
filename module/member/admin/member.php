<?php

include_once("../includes/page_utf_class.php");
include_once("../lang/$langs/company_type_config.php");
//====================================
if(!empty($_POST['de'])&&empty($_POST['passall']))
{
	del_user($_POST['de']);
}

if(!empty($_POST['passall']))
{	
	//审核所有会员
	$sql="update ".ALLUSER." set statu=2 where statu=1 or statu='' or statu is null";
	$db->query($sql);
	unset($sql);
}

function del_user($ar)
{
	global $db,$config;
	foreach($ar as $v)
	{	
		$userid=$v;
		
		ext_all('delete_by_uid',array('uid'=>$userid));
		$db->query("delete from ".ALLUSER." where userid='$userid'");
		$db->query("delete from ".SHOP." where userid='$userid'");
		$db->query("delete from ".FEED." where userid='$userid'");
		$db->query("delete from ".FEEDBACK." where touserid='$userid' or fromuserid='$userid'");
        $db->query("delete from ".COMMENT." where fromuid='$userid'");
		$db->query("delete from ".READREC." where userid='$userid'");
		$db->query("delete from ".SUBSCRIBE." where userid='$userid'");
		$db->query("delete from ".SHOPEARNEST." where shop_id='$userid'");
	}
}

unset($_GET['m']);
unset($_GET['s']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
<script type="text/javascript" src="../script/my_lightbox.js"></script>
<title><?php echo lang_show('admin_system');?></title>
</head>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('query');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
      <?php
		$categorys['user']=lang_show('user_id');
		$categorys['contact']=lang_show('contact');
		$categorys['tel']=lang_show('tel');
		$categorys['ip']='IP';
		$categorys['email']='E-mail';
	?>
      <form name="frmuser" method="get">
      <input type="hidden" name="m" value="member" />
      <input type="hidden" name="s" value="member.php" />
        <tr>
          <td height="22">负责管理员</td>
          <td><select class="select" name="invite">
              <option value=""><?php echo lang_show('all');?></option>
              <?php
		  	$sql="select name from ".ADMIN." where name!='' order by name";
			$db->query($sql);
			$invite=$db->getRows();
			foreach($invite as $v)
			{
		  ?>
              <option value="<?php echo $v['name']; ?>" <?php if($_GET['invite']==$v['name']) echo "selected"; ?>><?php echo $v['name']; ?></option>
              <?php
			 }
			?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="10%" height="22"><?php echo lang_show('statu');?></td>
          <td width="90%"><select class="select" name="only" id="only">
              <option value="" selected><?php echo lang_show('all_member');?></option>
              <?php
				foreach($member_group as $key=>$v)
				{
				?>
              <option value="<?php echo $key; ?>" <?php if($_GET['only']==$key) echo "selected"; ?>><?php echo $v; ?></option>
              <?php
				 }
				?>
            </select>
          </td>
        </tr>
        <tr>
          <td width="10%" height="22">显示方式</td>
          <td width="90%"><select class="select" name="ordrby">
              <option <?php if ($_GET['ordrby']=='lastLoginTime') echo "selected";?> value="lastLoginTime">按登录时间</option>
              <option <?php if ($_GET['ordrby']=='point') echo "selected";?> value="point">按积分</option>
              <option <?php if ($_GET['ordrby']=='rank') echo "selected";?> value="rank">按排名</option>
            </select>
            <input type="radio" class="radio" name="order" value="" <?php echo $_GET['order']==""?"checked":""?> />
            <?php echo lang_show('desc');?>
            <input type="radio" class="radio" name="order" value="desc" <?php echo $_GET['order']=="desc"?"checked":""?> />
            <?php echo lang_show('asc');?> </td>
        </tr>
        <tr>
          <td height="22"><?php echo lang_show('bylastlogotime');?></td>
          <td><select class="select" name="lastlogotime" id="lastlogotime">
              <option value=""><?php echo lang_show('alllogotime');?></option>
              <option value="1" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='1') echo "selected";?>><?php echo lang_show('oneh');?></option>
              <option value="2" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='2') echo "selected";?>><?php echo lang_show('twoh');?></option>
              <option value="12" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='12') echo "selected";?>><?php echo lang_show('halfd');?></option>
              <option value="24" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='24') echo "selected";?>><?php echo lang_show('oneday');?></option>
              <option value="48" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='48') echo "selected";?>><?php echo lang_show('twoday');?></option>
              <option value="72" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='72') echo "selected";?>><?php echo lang_show('threeday');?></option>
              <option value="168" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='168') echo "selected";?>><?php echo lang_show('oneweek');?></option>
              <option value="336" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='336') echo "selected";?>><?php echo lang_show('twoweek');?></option>
              <option value="720" <?php if (!empty($_GET['lastlogotime'])&&$_GET['lastlogotime']=='720') echo "selected";?>><?php echo lang_show('onemonth');?></option>
            </select>
          </td>
        </tr>
        <tr>
          <td height="22">过滤条件</td>
          <td><input type='checkbox' <?php echo $_GET['is_email']=="1"?"checked":""?> value='1' name='is_email' />
            邮箱
            <input type='checkbox' <?php echo $_GET['is_tel']=="1"?"checked":""?> name='is_tel' value='1' />
            电话
            <input type='checkbox' <?php echo $_GET['is_mobile']=="1"?"checked":""?> name='is_mobile' value='1' />
            手机 </td>
        </tr>
        <tr>
          <td height="22">关键词</td>
          <td><select class="lselect" name="category">
              <?php foreach($categorys as $keys=>$v){ ?>
              <option value="<?php echo $keys;?>" <?php if($_GET['category']==$keys ) echo "selected";?> ><?php echo $v; ?></option>
              <?php } ?>
            </select>
            --
            <input class="rtext" name="keyword" type="text" value="<?php echo $_GET['keyword'];?>" size="40" /></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('search');?>" /></td>
        </tr>
      </form>
    </table>
  </div>
</div>
<div style="float:left; height:20px; width:80%;">&nbsp;</div>
<?php if(isset($_GET['Submit'])){ ?>
<form method="post">
  <div class="bigbox">
    <div class="bigboxhead"><?php echo lang_show('member_list');?></div>
    <div class="bigboxbody">
      <table width="100%" border="0" cellspacing="0" cellpadding="1" align="center" class="menu">
        <tr class="theader">
          <td width="66"  align="center" ><input name="checkbox" type="checkbox" class="checkbox" onClick="do_select()" value="checkbox"></td>
          <td width="175"  align="left" ><?php echo lang_show('member_id');?></td>
          <td width="251"  align="left" >联系人/电话</td>
          <td width="152"  align="left" >最后登录时间</td>
          <td width="207" align="left" ><?php echo lang_show('reg_time');?></td>
          <td width="84" align="left" >积分</td>
          <td width="106" align="center" ><?php echo lang_show('manager');?></td>
        </tr>
        <?php
  	unset($_GET['userid']);
  	$getstr=implode('&',convert($_GET));$scl=NULL;
  	
	if(!empty($_GET['ordrby']))
		$ordrby=''.$_GET['ordrby'];
	else
		$ordrby='lastLoginTime';
	
	if(!empty($_GET['keyword']))
	{
		if($_GET['category']=='ip')
			$scl.=" and ip='$_GET[keyword]'";
		if($_GET['category']=='email')
			$scl.=" and email='$_GET[keyword]'";
		else
			$scl.=" and $_GET[category] regexp '$_GET[keyword]'";
	}
	if(!empty($_GET['only']))
		$scl.=" and statu='".$_GET['only']."'";
	if($_SESSION['province'])
		$scl.=" and province='$_SESSION[province]'";
	if($_SESSION['city'])
		$scl.=" and city='$_SESSION[city]'";
	if($_SESSION['area'])
		$scl.=" and area='$_SESSION[area]'";	
	//==================================
	if(!empty($_GET['is_email']))
		$scl.=" and email!='' ";
	if(!empty($_GET['is_tel']))
		$scl.=" and tel!='' ";
	if(!empty($_GET['is_mobile']))
		$scl.=" and mobile!='' ";
	if(!empty($_GET['invite']))
		$scl.=" and invite='$_GET[invite]'";

	$sql="SELECT * from ".ALLUSER."  WHERE 1 $scl";
	if($_GET['order']) 
	   $sql.=" order by $ordrby asc";  
	else 
	   $sql.=" order by $ordrby desc ";
	//=============================
	$page = new Page;
	$page->listRows=100;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",100";
	$pages = $page->prompt();
	//=====================
	$db->query($sql);
	$i=0;
	foreach($db->getRows() as $v)
	{
?>
        <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td width="66" align="center"><input name="de[]" type="checkbox" class="checkbox" id="de<?php echo $i++?>" value="<?php echo !empty($v['userid'])?$v['userid']:$v['buid']; ?>">
          </td>
          <td width="175" align="left"> [<?php echo $member_group[$v['statu']];?>]<a href="<?php echo $config['weburl']; ?>/shop.php?uid=<?php echo !empty($v['userid'])?$v['userid']:$v['buid']; ?>" target="_blank"> <?php echo htmlspecialchars($v['user']); ?> </a> </td>
          <td align="left"><?php echo $v['contact']; if(!empty($v['tel'])){ echo '&nbsp;'.lang_show('tel').':'. $v['tel']; }?></td>
          <td align="left"><?php echo date("Y-m-d H:i",$v['lastLoginTime']); ?></td>
          <td align="left"><?php echo $v['regtime']; ?></td>
          <td align="left">&nbsp;<?php echo $v['point']; ?></td>
          <td align="center">
          
          <a onclick="alertWin('<?php echo lang_show('mem_send_mail');?>','','650','600','sendmail.php?userid=<?php echo !empty($v['userid'])?$v['userid']:$v['buid']; ?>');" title="<?php echo lang_show('send_mail');?>" href="#"><?php echo $mailimg;?></a> 
          
          <a href="?m=member&s=membermod.php&userid=<?php echo (!empty($v['userid'])?$v['userid']:$v['buid']).'&'.$getstr; ?>"><?php echo $editimg;?></a> 
          
          <a title="<?php echo lang_show('enteroffice');?>" href="to_login.php?action=submit&user=<?php echo $v['user']; ?>" target="_blank"><?php echo $setimg;?></a> </td>
        </tr>
        <?php
	}
	?>
      </table>
      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="45%" height="24"><input class="btn" type="submit" name="Submit2" value="<?php echo lang_show('delete');?>" onClick='return confirm("<?php echo lang_show('are_you_sure');?>");'>
            <input class="btn" type="submit" name="passall" value="<?php echo lang_show('passunreg');?>" id="passall">
          </td>
          <td width="65%" align="right"><div class="page"><?php echo $pages?></div></td>
        </tr>
      </table>
    </div>
  </div>
</form>
<?php } ?>
</body>
</html>
