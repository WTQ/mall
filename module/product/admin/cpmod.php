<?php
include_once("../includes/tag_inc.php");
//========================
if(isset($_POST["action"]))
{
	$_POST['rank']*=1;
	$sql="update ".PRO." set pname='$_POST[pname]',rank='$_POST[rank]' where id='$_POST[id]'";
	$db->query($sql);
	
	$sql="UPDATE ".PRODETAIL."  set detail='$_POST[prodetail]' WHERE proid=$_POST[id]";
	$db->query($sql);
	
	edit_tags($_POST['keyword'],1,$_POST['id']);
	
	unset($_GET['id']);
	$_GET['s']='prolist.php';
	msg("module.php?".implode('&',convert($_GET)));
}
//===================
$db->query("select * from ".PRO." where id='$_GET[id]' ");
while($db->next_record())
{
	$id=$db->f('id');
	$userid=$db->f('userid');
	$pname=$db->f('pname');
	$rank=$db->f('rank');
	$keyword=get_tags($id,1);
}
$db->query("select * from ".PRODETAIL." where proid='$_GET[id]'");
$re=$db->fetchRow();
$detail=$re['detail'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('edit');?> </div>
  <div class="bigboxbody">
  	<div class="notice">
	因为商城的所有产品是用来交易的，产品发布时有多种规格和内容要填，交易过程中也有很多环节相关联，
	并且商家所发商品是私有的，在没有经过会员允许的情可下请不要擅自修改会员的产品信息．因此我们只保留极个别信息可以编辑．</div>
    <form name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="4" cellspacing="0">
        <tr>
          <td width="147" align="left"><?php echo lang_show('product_name');?></td>
          <td><input class="text" name="pname" type="text" id="goods" value="<?php echo "$pname";?>" size="30">
            <input name="id" type="hidden" id="id" value="<?php echo "$id";?>">
          </td>
        </tr>
        <tr>
          <td align="left"><?php echo lang_show('keyword');?></td>
          <td><input class="text" name="keyword" type="text" value="<?php echo $keyword?>" size="30"></td>
        </tr>
        <tr>
          <td align="left"><?php echo lang_show('pro_detail');?></td>
          <td> 
		<script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
        
        <script>
			var editor;
				KindEditor.ready(function(K) {
					editor = K.create('textarea[name="prodetail"]', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					langType :'<?php echo $config['language']; ?>',
				});
			});
        </script>
        <textarea name="prodetail" style="width:90%; height:400px;"><?php echo $detail; ?></textarea>
		  
          </td>
        </tr>
        <tr>
          <td align="left"><?php echo lang_show('competitive_ranking');?></td>
          <td align="left"><input class="text" name="rank" type="text" id="rank" value="<?php echo $rank?>" size="30"></td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left"><input name="cc1" class="btn" type="submit" id="cc1" value=" <?php echo lang_show('modify');?> ">
            <input name="action" type="hidden" id="action" value="submit"></td>
        </tr>
      </table>
    </form>
  </div>
</div>
</body>
</html>
