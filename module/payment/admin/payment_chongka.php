<?php
$total_price=$_POST['total_price']*1;

if($total_price>0)
{
	$str="abcdefghijklmnopqrstuvwxyz";
	$num=1*$_POST['num'];
	
	for($j=0;$j<$num;$j++)
	{
		$password='';
		for($i=0;$i<8;$i++)
			$password.=$str[rand(0,25)];
		$card_num=rand(1000000000,9999999999);
		$sql="INSERT INTO ".PAYCARD." (`card_num` ,`total_price` ,`password` 
		,`statu` ,`creat_time` ,`pic`,`stime`,`etime` )VALUES('$card_num','$total_price',
		'$password', '0', '".time()."', '$_POST[pic]','".($_POST['stime']?strtotime($_POST['stime']):0)."','".($_POST['etime']?strtotime($_POST['etime']):0)."')";
		$db->query($sql);
	}	
	msg("module.php?m=payment&s=payment_chongka.php");
}
$id=$_GET['id']*1;
if($id)
{
	$sql="select a.*,b.name from  ".PAYCARD." a 
	left  join ".ALLUSER." b on a.uid=b.userid where a.id='$id'  order by id desc limit 1";
	$db->query($sql);
	$re=$db->fetchRow();
	unset($GET['total_price']);
}
$deid=$_GET['deid'];
if($deid)
{
	$sql="delete from ".PAYCARD."  where  id='$deid' ";
	$db->query($sql);
	$re=$db->fetchRow();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<script src="../script/my_lightbox.js" language="javascript"></script>
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead">
     <span class="cbox <?php if(empty($_GET['type'])){?>on<?php } ?>"><a href="module.php?m=payment&s=payment_chongka.php">充值卡管理</a></span>
    <span class="cbox <?php if(!empty($_GET['type'])){?>on<?php } ?>"><a href="module.php?m=payment&s=payment_chongka.php&type=1">生成充值卡</a></span>
    </div>
	<div class="bigboxbody">
     <?php if(!empty($_GET['type'])){ ?>
     <form name="form1" method="post" action="">
     <table width="99%" border="0" cellpadding="4" cellspacing="0">
        <tr>
          <td width="8%" align="left">充值面额</td> <td width="100" align="left"><input class="text"  name="total_price"  type="text" value="<?php echo ($re['total_price']||$_POST['total_price'])?($re['total_price']?$re['total_price']:$_POST['total_price']):50;?>"/></td>
        </tr>
         <tr>
          <td width="8%" align="left">生成数量</td><td width="100" align="left"><input class="text"  name="num"  type="text" value="1"/></td>
        </tr>
        <tr>
          <td width="8%" align="left">有效时间</td><td>
          <script language="javascript" src="../script/Calendar.js"></script>
           <script language="javascript">
                var cdr = new Calendar("cdr");
                document.write(cdr);
                cdr.showMoreDay = true;
           </script>
          <input  onFocus="cdr.show(this);" class="ltext" type="text" name="stime" size="15" value="<?php if(!empty($_POST['stime'])){echo $_POST['stime']; } ?>"> -- <input  onFocus="cdr.show(this);" class="rtext" type="text" name="etime" size="15" value="<?php if(!empty($_POST['etime'])){ echo $_POST['etime'];} ?>"></td>
        </tr>
        <tr>
        <td>卡片图案</td>
        <td><input name="pic" type="text" id="pic" class="text" value="<?php echo $re['pic']; ?>">
		 [<a href="javascript:uploadfile('上传LOGO','pic',180,60,'')">上传</a>] 
		 [<a href="javascript:preview('pic');">预览</a>]
		 [<a onclick="javascript:$('#pic').val('');" href="#">删除</a>]
         </td>
        </tr>
         <tr>
          <td width="8%" align="left"></td> 
          <td align="left"><input class="btn" type="submit"  value="提交" /></td>
        </tr>
       </table>
      </form>
    <?php
	 }
	 else
	 {
	?>
  <table width="99%" border="0" cellpadding="2" cellspacing="0" >
	<tr class="theader">
	  <td width="15%" >卡号</td>
	  <td width="19%" >密码</td>
	  <td width="10%" align="left" >面额</td>
	  <td width="13%" align="center" >创建时间</td>
	  <td width="12%" align="center" >有效时间</td>
	  <td width="13%" align="center" >状态</td>
	  <td width="9%" align="center" ><span>使用人</span></td>
	  <td width="9%" align="center" ><?php echo lang_show('pickuplist_g');?></td>
	</tr>
    <?php
	$sql="SELECT  *  FROM  ".PAYCARD."  WHERE  1=1  $psql ";
	//=============================
	include_once("../includes/page_utf_class.php");
	$page = new Page;
	$page->listRows=20;
	if (!$page->__get('totalRows')){
		$db->query($sql);
		$page->totalRows = $db->num_rows();
	}
	$sql .= "  limit ".$page->firstRow.",20";
	$pages = $page->prompt();
	//=============================
	$db->query($sql);
	$re=$db->getRows();
	$coun_num=$db->num_rows();
	for($i=0;$i<$coun_num;$i++)
	{ 
	?>
     <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
         <td><?php echo $re[$i]["card_num"]; ?></td>
         <td align="left"><?php echo $re[$i]["password"]; ?></td>
         <td align="left"><?php echo $re[$i]["total_price"]; ?></td>
         <td align="center"><?php echo dateformat($re[$i]["creat_time"]); ?></td>
         <td align="center"><?php echo $re[$i]["stime"]&&$re[$i]["etime"]?dateformat($re[$i]["stime"])."/".dateformat($re[$i]["etime"]):'0/0'; ?></td>
         <td align="center"  ><?php echo $_GET['statu']?'已使用':'未使用'; ?></td>
         <td align="center"><?php echo $re[$i]["use_name"]; ?></td>
         <td align="center">
         <a href="?deid=<?php echo $re[$i]["id"];?>&m=payment&s=payment_chongka.php&type=1" title="<?php echo lang_show('delete');?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>')"><?php echo $delimg;?></a></td>
      </tr>
	<?php 
     }
     ?>
      </table>
	  <div class="page"><?php echo $pages?></div>
      <?php } ?>
  </div>
</div>
</body>
</html>