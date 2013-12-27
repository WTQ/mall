<?php

include_once("../includes/page_utf_class.php");
//=======================
if(!empty($_GET['delall'])&&is_array($_GET['de']))
{
	$id=implode(",",$_GET['de']);
	if($id)
	{
		$sql="delete from ".COMMENT. " where id in ($id)";
		$db->query($sql);
	}
}
if(!empty($_GET['action'])&&$_GET['action']=='del'&&!empty($_GET['id']))
{
	$sql="delete from ".COMMENT. " where id=".$_GET['id'];
    $db->query($sql);
}
$subsql='';
if (!empty($_GET['username']))
{
    $sqlc="select userid from ".ALLUSER." where user='".$_GET['username']."'";
	$db->query($sqlc);
	$us=$db->fetchRow();
	$subsql.=" and fromuid='".$us['userid']."'";
}
if(!empty($_GET['rewtype']))
{
	$subsql.=" and ctype='".$_GET['rewtype']."'";
}
if(!empty($_GET['usergroup']))
{
	$sqlu="select userid from ".USER." where ifpay='".$_GET['usergroup']."'";
	$db->query($sqlu);
	$uus=$db->getRows();
	$mys=array();
	foreach($uus as $uk)
	{
		$mys[]=$uk['userid'];
	}
	$ut=implode(",", $mys);
	if(empty($ut))
		$ut=0;
	$subsql.=" and fromuid  in (".$ut.")";
}
if(!empty($_GET['conid']))
{
	$subsql.=" and conid='".$_GET['conid']."'";
}
$sqlall="select count(*) as num from ".COMMENT." where 1=1 $subsql  order by id desc";
$db->query($sqlall);
$renum=$db->fetchRow();


		
$sql="select * from ".COMMENT." where 1=1 $subsql  order by id desc";
//=============================
$page = new Page;
$page->listRows=10;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  limit ".$page->firstRow.",20";
$pages = $page->prompt();
//=============================
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('urewiew');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="main.js"></script>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("de[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("de[]")[j].checked==true)
	  	  document.getElementsByName("de[]")[j].checked = false;
		else
		  document.getElementsByName("de[]")[j].checked = true;
	 }
}
</script>
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('allrewiew');?></div>
  <div class="bigboxbody">
    <form method="get" action="">
    <input type="hidden" name="m" value="member" />
    <input type="hidden" name="s" value="user_rewiew.php" />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="searh_left"><?php echo lang_show('uname');?></td>
          <td><input type="text" class="text" name="username" id="username" value="<?php echo $_GET['username'];?>" /></td>
        </tr>
        <tr>
          <td><?php echo lang_show('rtype');?></td>
          <td><select class="select"  name="rewtype" id="ordertype">
              <option value="" selected><?php echo lang_show('typeall');?></option>
              <option value="1" <?php if($_GET['rewtype']==1) echo 'selected';?>><?php echo lang_show('news');?></option>
              <option value="4" <?php if($_GET['rewtype']==4) echo 'selected';?>><?php echo lang_show('exh');?></option>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo lang_show('ugroup');?></td>
          <td><select class="select"  name="usergroup" id="usergroup">
              <option value="" selected><?php echo lang_show('allgroup');?></option>
              <?php
            $sql="select * from ".USERGROUP;
            $db->query($sql);
            $usr=$db->getRows();
            foreach($usr as $u)
	          {
           ?>
              <option value="<?php echo $u['group_id']; ?>" <?php if(!empty($_GET["usergroup"])&&$_GET["usergroup"]==$u['group_id']) echo "selected"; ?>><?php echo $u['name']; ?></option>
              <?php
              }
           ?>
            </select>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input class="btn" type="submit" name="Submit" value="<?php echo lang_show('submit');?>" />
          </td>
        </tr>
      </table>
    </form>
    <form action="" method="get">
    
    <input type="hidden" name="m" value="member" />
    <input type="hidden" name="s" value="user_rewiew.php" />
      <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr class="theader">
          <td width="24" ><input type="checkbox" class="checkbox" name="checkbox2" id="checkbox2" onClick="do_select();">
          </td>
          <td width="53"><?php echo lang_show('reuser');?></td>
          <td ><?php echo lang_show('retype');?></td>
          <td ><?php echo lang_show('rtitle');?></td>
          <td ><?php echo lang_show('recon');?></td>
          <td ><?php echo lang_show('rtime');?></td>
          <td align="center" ><?php echo lang_show('react');?></td>
        </tr>
        <?php
		  $i=0;
	      foreach ($re as $v)
          {
	?>
        <tr  onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
          <td  width="24"><input type="checkbox" class="checkbox" name="de[]" value="<?php echo $v['id'];?>"></td>
          <td><?php
		$sql="select company from ".USER." where userid=".$v['fromuid'];
        $db->query($sql);
		 $un=$db->fetchRow();
		 if(empty($un['company']))
			 echo lang_show('noname');
		 else
			echo '<a href="'.$config['weburl'].'/shop.php?uid='.$v['fromuid'].'">'.$un['company'].'</a>';
			?>
          </td>
          <td width="91" ><?php 
		if ($v['ctype']==1)
		    echo lang_show('news');
	    elseif($v['ctype']==2)
            echo lang_show('info');
		elseif($v['ctype']==3)
            echo lang_show('pro');
		else
            echo lang_show('exh');
	    ?></td>
          <td width="313" ><?php 
            if ($v['ctype']==1)
			  {
				$sql="select ftitle,uid from ".NEWSD." where nid=".$v['conid'];
				$db->query($sql);
				$ct=$db->fetchRow();
				if(empty($ct['userid']))
				   echo '<a href="'.$config['weburl'].'/news_detail.php?id='.$v['conid'].'">'.$ct['title'].'</a>';
				else
					echo '<a href="'.$config['weburl'].'/shop.php?uid='.$ct['userid'].'&action=newsd&id='.$v['conid'].'" target="_blank">'.$ct['title'].'</a>';
			  }
		  elseif($v['ctype']==3)
			  {
			  $sql="select pname,userid from ".PRO." where id=".$v['conid'];
			  $db->query($sql);
				$ct=$db->fetchRow();
				echo '<a href="'.$config['weburl'].'/?m=product&s=detail&id='.$v['conid'].'" target="_blank">'.$ct['pname'].'</a>';
			  }
		  else
			  {
              $sql="select title  from ".EXHIBIT." where id=".$v['conid'];
			  $db->query($sql);
			  $ct=$db->fetchRow();
			 echo '<a href="'.$config['weburl'].'/?m=exhibition&s=exhibition_detail&id='.$v['conid'].'" target="_blank">'.$ct['title'].'</a>';
			  }

			?></td>
          <td width="304" ></a>
            <textarea name="content" type="text" id="content"  style="width:300px;height:60px;"><?php echo $v['content']; ?></textarea></td>
          <td width="94" ><?php echo date("Y-m-d H:m",$v['uptime']); ?></td>
          <td width="51" align="center"><span ><a href="?m=member&s=user_rewiew.php&action=del&id=<?php echo $v['id'];?>"><?php echo $delimg;?></a></span></td>
        </tr>
        <?php 
		$i++;
    }
	?>
        <tr>
          <td colspan="4" align="left"><input  class="btn" type="submit" name="delall" id="button" value="<?php echo lang_show('redel');?>"></td>
          <td colspan="4" align="right"><div class="page"><?php echo $pages;?></div></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
