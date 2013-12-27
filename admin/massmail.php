<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</HEAD>
<body>
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../script/district.js" ></script>
<script>
var weburl="<?php echo $config["weburl"]; ?>";
</script>
<style>.hidden{ display:none;}</style>
<script type="text/javascript">
function checkm()
{
	if (document.mailsend.subject.value==""||document.mailsend.mtype.value=="")
	{ 
		alert("<?php echo lang_show('isnullmsg');?>") 
		return false;  
	}  
	else 
		return true;
}
function show()
{
	var d=document.getElementById ("addsend").style.display = "block";
	var d=document.getElementById ("addsend1").style.display = "block";
}
function hid(n)
{
	var d=document.getElementById ("addsend").style.display = "none";
	var d=document.getElementById ("addsend1").style.display = "none";

}
</script>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('send_mail_multiply');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15%" valign="top"><div style="border-right: 1px solid #999999; height:400px; line-height:23px; overflow-y:scroll;"> <strong><?php echo lang_show('please_select_mail_tpl');?></strong><br>
            <?php
        $db->query("select * from ".MAILMOD." where flag='' or flag is NULL order by id desc");
        while($db->next_record())
        {
        $id=$db->f('id');
        $subject=$db->f('subject');
        echo " &raquo; <a href=massmail.php?modid=$id>$subject</a><br>";
        }
        ?>
          </div></td>
        <td width="85%" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <form method="post" action="mail_send.php" name="mailsend">
              <tr>
                <td style="border-top:none;" width="5%" ><?php echo lang_show('send_to');?></td>
                <td style="border-top:none;">
                <div style="float:left">
                 
    <input type="hidden" name="province" id="id_1" value="<?php echo getdistrictid($de["province"]); ?>" /> 
    <input type="hidden" name="city" id="id_2" value="<?php echo getdistrictid($de["city"]); ?>" />
    <input type="hidden" name="area" id="id_3" value="<?php echo getdistrictid($de["area"]); ?>" />

     
     
        <select id="select_1" size="10" onChange="selClass(this);">
        <option value="">--请选择--</option>
		<?php echo GetDistrict(); ?>
        </select>
        
        <select id="select_2" size="10" onChange="selClass(this);" class="hidden"></select>
        <select id="select_3" size="10" onChange="selClass(this);" class="hidden"></select>
        </div>
       
        
                
                  <select name="catid[]" size="10" multiple style="width:100px; float:left; margin-left:5px;">
                    <option selected="selected" style="font-weight:bold;" value=""><?php echo lang_show('all_industry');?></option>
                    <?php
					$sql="select id,name from ".SHOPCAT."  where parent_id=0 order by displayorder ,id";
							$db->query($sql);
							while($db->next_record())
							{
								$datacatid=$db->f("id");
								$datacat=$db->f("name");
								echo "<option value=$datacatid>&nbsp;&nbsp;$datacat</option>"; 
							}
							?>
                  </select>
                  <select name="unlogotime" size="10" style="width:100px; float:left; margin-left:5px;">
                    <option selected="selected" style="font-weight:bold;" value=""><?php echo lang_show('alllogot');?></option>
                    <option value="7"><?php echo lang_show('owunlogo');?></option>
                    <option value="14"><?php echo lang_show('twunlogo');?></option>
                    <option value="30"><?php echo lang_show('omunlogo');?></option>
                    <option value="60"><?php echo lang_show('tmunlogo');?></option>
                    <option value="90"><?php echo lang_show('thmunlogo');?></option>
                    <option value="180"><?php echo lang_show('sixmunlogo');?></option>
                    <option value="360"><?php echo lang_show('oyunlogo');?></option>
                  </select>
                </td>
              </tr>
              <tr>
                <td ><?php echo lang_show('mtype');?></td>
                <td ><input checked="checked" type="radio" class="radio" name="mtype" id="radio" value="1" onClick="hid();">
                  <?php echo lang_show('mmail');?>
                  <input type="radio" class="radio" name="mtype" id="radio2" value="2" onClick="hid();">
                  <?php echo lang_show('msms');?>
                  <input type="radio" class="radio" name="mtype" id="radio3" value="3" onClick="show();">
                  <?php echo lang_show('mmsg');?></td>
              </tr>
              <tr>
                <td><?php
				if(!empty($_GET['modid']))
				{
					$modid=$_GET['modid'];
					$db->query("select * from ".MAILMOD." where id='$modid'");
					$re=$db->fetchRow();
				}
				?>
                  <div id="addsend1" style="display:none;"> 发件人信息 </div></td>
　
                <td><div id="addsend" style="display:none;"> <?php echo lang_show('contact');?>
                    <input name="contact" type="text"  value="" style="width:410px;">
                    <br />
                    <?php echo lang_show('email');?>
                    <input name="email" type="text"  value="" style="width:410px;">
                    <br />
                    <?php echo lang_show('tel');?>
                    <input name="tel" type="text"  value="" style="width:410px;">
                    <br />
                    (如果发件人信息为空，将以管理员身份发送邮件，邮件性质为“系统消息”) </div></td>
              </tr>
              <tr>
                <td valign="top" ><?php echo lang_show('mail_subject');?> </td>
                <td valign="top" ><input name="subject" type="text"  value="<?php echo $re["subject"]; ?>" style="width:500px;"></td>
              </tr>
              <tr>
                <td width="16%" valign="top" ><?php echo lang_show('mail_content');?></td>
                <td width="84%" valign="top" >
				<script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>
                            
			<script>
            var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name="mes"]', {
                   langType :'<?php echo $config['language']; ?>',
                });
            });
            </script>
            <textarea name="mes" style="width:90%; height:400px;"><?php echo $re["message"]; ?></textarea>
            
                </td>
              </tr>
              <tr>
                <td width="16%" style="border-bottom:none;">&nbsp;</td>
                <td width="84%" style="border-bottom:none;"><input class="btn" type="submit" name="submit" value="<?php echo lang_show('send_mail');?>" onClick="return checkm();">
                  <input name="action" type="hidden" id="action" value="send"></td>
              </tr>
            </form>
          </table></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>
