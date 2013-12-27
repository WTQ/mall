<?php

include_once("../includes/page_utf_class.php");
//====================================
if(!empty($_GET['delall']))
{
	$sql="delete from ".READREC;
	$db->query($sql);	
}
if(!empty($_GET['del'])&&$_GET['del']==lang_show('delete')&&is_array($_GET["checktag"]))
{
	$delid=implode(",",$_GET["checktag"]);
	$sql="delete from ".READREC."  where  id in (".$delid.")";
	$db->query($sql);	
}
if(!empty($_GET['username']))
{
	$sqlk="select userid from ".ALLUSER." where user='".$_GET['username']."'";
	$db->query($sqlk);
	$urs=$db->fetchRow();
	if($urs['userid'])
		$psql=" and b.userid=".$urs['userid'];
}

$sql="select * from ".READREC." a, ".USER." b where a.userid=b.userid $psql";
//------------------------------------

$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query($sql);
	$page->totalRows = $db->num_rows();
}
$sql .= "  order by time desc limit ".$page->firstRow.",20";
$pages = $page->prompt();
//-----------------------------------
$db->query($sql);
$re=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('user_admin');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">
function checkall()
{
	 for(var j = 0 ; j < document.getElementsByName("checktag[]").length; j++)
	 {
	  	if(document.getElementsByName("checktag[]")[j].checked==true)
	  	  document.getElementsByName("checktag[]")[j].checked = false;
		else
		  document.getElementsByName("checktag[]")[j].checked = true;
	 }
}
</script>
<div class="guidebox"><?php echo lang_show('user_admin');?> &raquo; <?php echo lang_show('user_read_rec');?></div>
<div class="bigbox">
<div class="bigboxhead"><?php echo lang_show('user_read_rec');?></div>
	<div class="bigboxbody">
	<form name="" action="" method="get">
    <input type="hidden" name="m" value="member" />
    <input type="hidden" name="s" value="user_read_rec.php" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr >
          <td colspan="5" align="left" >
            <?php echo lang_show('uname');?>
            <input value="<?php echo $_GET['username'];?>" name="username" type="text" id="username" class="text" maxlength="20">
            <input class="btn" type="submit" name="research" id="research" value="<?php echo lang_show('isure');?>">
			<input class="btn" onclick="window.location='?m=member&s=user_read_rec.php&delall=1';" name="" type="button" value="一键清空" />
			</td>
          </tr>
        <tr class="theader">
          <td width="25" align="left">
            <input type="checkbox" class="checkbox" name="checktagall" id="checktagall" onClick="checkall()">
          </td>
          <td width="260" align="left"><?php echo lang_show('uname');?></td>
          <td width="160" align="left"> <?php echo lang_show('conttype');?></td>
          <td width="307" align="left"><?php echo lang_show('contitle');?></td>
          <td width="202" align="left"><?php echo lang_show('readtime');?></td>
        </tr>
        <?php
	      foreach ($re as $v)
          {
			  $sql="select a.user,b.company from ".ALLUSER." a,".USER." b  where a.userid=b.userid and a.userid=".$v['userid'];
			  $db->query($sql);
			  $de=$db->fetchRow();
        ?>
        <tr>
          <td align="left" >
            <input type="checkbox" class="checkbox" name="checktag[]" value="<?php echo $v['id'];?>">          </td>
          <td align="left" ><?php echo $de['user'];?>--<?php echo $de['company'];?></td>
          <?php 
          if ($v['type']==1)
          {
             $rtype=lang_show('typeo');
             $sql="select id,userid,pname from ".PRO." where id=".$v['tid'];
             $db->query($sql);
			 $de=$db->fetchRow();
             $rname="<a href=\"".$config['weburl']."/?m=product&s=detail&id=".$de['id']."\" target=\"_blank\">".$de['pname']."</a>";
          }
          elseif($v['type']==3)
          {
             $rtype=lang_show('types');
             $sql="select userid,company from ".USER." where userid=".$v['tid'];
             $db->query($sql);
			 $de=$db->fetchRow();
             $rname="<a href=\"".$config['weburl']."/shop.php?uid=".$de['userid']."&com=".$de['company']."\" target=\"_blank\">".$de['company']."</a>";
          }
          elseif($v['type']==4)
          {
             $rtype=lang_show('typef');
             $sql="select nid,ftitle from ".NEWSD." where nid=".$v['tid'];
             $db->query($sql);
			 $de=$db->fetchRow();
             $rname="<a href='".$config['weburl']."/?m=news&s=news_detail&id=".$de['newsid']."' target='_blank'>".$de['title']."</a>";
           }
		 elseif($v['type']==5)
          {
             $rtype=lang_show('typew');
             $sql="select id,title from ".EXHIBIT." where id=".$v['tid'];
             $db->query($sql);
			 $de=$db->fetchRow();
             $rname="<a href=\"".$config['weburl']."/?m=exhibition&s=exhibition_detail&id=".$de['id']."\" target=\"_blank\">".$de['title']."</a>";
           }
          ?>
          <td align="left" ><?php echo $rtype;?></td>
          <td align="left" ><?php echo $v['tid'];?>--<?php echo $rname;?></td>
          <td  align="left" ><?php echo date("Y-m-d H:i:s",$v['time']);?></a></td>
        </tr>
        <?php
        }
        ?>
        <tr>
          <td  colspan="3" align="left">
            <input class="btn" type="submit" name="del" id="del" value="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');"></td>
          <td  colspan="2" align="right"><div class="page"><?php echo $pages;?></div></td>
        </tr>
</table>
	</form>
	</div>
</div>
</body>
</html>