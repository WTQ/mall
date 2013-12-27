<?php
//==========================
if(!empty($_POST['submit'])&&!empty($_GET['edit']))
{
	$p=$_POST;
	$p['stime']=strtotime($p['stime']);
	$p['etime']=strtotime($p['etime']);
	$p['user_group']=implode(",",$_POST['user_group']);
	$order=empty($p['order'])?0:$p['order']*1;
	
	$sql="update ".SPE." 
		set name='$p[name]',stime='$p[stime]',etime='$p[etime]',template='$p[template]',layout='$p[layout]',
		keyword='$p[keyword]',con='$p[con]',`order`='$order',user_group='$p[user_group]',
		file_name='$p[file_name]' where id='$_GET[edit]'";
	$db->query($sql);
	msg('module.php?m=special&s=special_list.php');
}
if(!empty($_POST['submit'])&&empty($_GET['edit']))
{
	$p=$_POST;
	$p['stime']=strtotime($p['stime']);
	$p['etime']=strtotime($p['etime']);
	$p['user_group']=implode(",",$_POST['user_group']);
	$order=empty($p['order'])?0:$p['order']*1;
	
	$sql="insert into ".SPE." 
	(name,stime,etime,template,layout,keyword,con,`order`,user_group,file_name,add_time,add_user) 
	values ('$p[name]','$p[stime]','$p[etime]','$p[template]','$p[layout]','$p[keyword]','$p[con]','$order','$p[user_group]','$p[file_name]','".time()."','$_SESSION[ADMIN_USER]')";
	$db->query($sql);
	$id=$db->lastid();	
	msg('module.php?m=special&s=special_list.php');
}
if(!empty($_GET['edit']))
{
	$sql="select * from ".SPE." where id='$_GET[edit]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
if(empty($de['stime']))
	$de['stime']=time();
if(empty($de['etime']))
	$de['etime']=time()+3600*24*30;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
<style>
.logo img{max-height:400px; max-width:400px}
</style>
</HEAD>
<body>
<form action="" method="post" enctype="multipart/form-data">
<div class="bigbox">
	<div class="bigboxhead">增加专题</div>
	<div class="bigboxbody">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" class="body_left"><span class="bz">专题制作对技术水平要求较高，需要具备一定html,Css知识,我们仅提供线上教程，不提供其它额外服务。</span></td>
    </tr>
  <tr>
    <td class="body_left">专题名称</td>
    <td width="85%"><input type="text" name="name" id="name" value="<?php echo $de['name'];?>" class="text"></td>
  </tr>
  <tr>
    <td>SEO关键字</td>
    <td><input name="keyword" type="text" id="keyword" value="<?php echo $de['keyword'];?>" class="text"></td>
  </tr>
  <tr>
    <td>专题描述</td>
    <td>
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
    <textarea name="con" style="width:90%; height:400px;"><?php echo $de["con"] ?></textarea>
		</td>
  </tr>
  <tr>
    <td>有权查看</td>
    <td><?php
		$groupid=explode(",",$de['user_group']);
		$sql="select * from ".USERGROUP." order by group_id asc ";
		$db->query($sql);
		$re=$db->getRows();
		foreach($re as $v)
		{
		if($v['group_id']>0){
	   ?> 
	   <input name="user_group[]" type="checkbox" class="checkbox" <?php if(!empty($groupid)&&in_array($v['group_id'],$groupid)) echo "checked"; ?> value="<?php echo $v['group_id']; ?>">&nbsp;<?php echo $v['name']; ?>&nbsp;&nbsp;
	   <?php
		} }
	   ?>       </td>
  </tr>

  <tr>
  <td>专题有效期</td>
    <td>
    <script language="javascript">
	var cdr = new Calendar("cdr");
	document.write(cdr);
	cdr.showMoreDay = true;
	</script>
			<input readonly name="stime" type="text" id="stime" class="ltext" value="<?php if(isset($de["stime"])){echo date("Y-m-d",$de["stime"]);}?>" onFocus="cdr.show(this);">
         --
            <input readonly onFocus="cdr.show(this);" name="etime" type="text" id="etime" class="rtext" value="<?php if(isset($de["etime"])){echo date("Y-m-d",$de["etime"]);}?>" onFocus="cdr.show(this);">    </td>
  </tr>
      <tr>
    <td>专题排序</td>
    <td><input class="text" type="text" name="order" id="order" value="<?php echo $de['order'];?>"></td>
  </tr>
  <tr>
    <td>模板</td>
    <td>
    <select class="select" name="template" id="template">
         <?php
		 function read_dir($dir)
		{
			$i=0;
			$handle = opendir($dir); 
			$rdir=array();
			while ($filename = readdir($handle))
			{ 
				if($filename!="."&&$filename!="..")
				{
				  if(!is_dir($dir.$filename))
				  { 
						$rdir[]=$filename;
				  }
			   }
			}
			return $rdir;
		}
		  $dir=read_dir("$config[webroot]/module/$_GET[m]/special_template/");
		  foreach($dir as $v)
		  {
		  	if($v==$de['template'])
				$sl="selected";
			else
				$sl=NULL;
		  	echo "<option value='$v' $sl>$v</option>";
		  }
		  ?>
          </select>     </td>
  </tr>
  <tr>
    <td>模板布局</td>
    <td>
    <input <?php if($de['layout']=='top,chenter,footer'){ echo 'checked';}?> type="radio" class="radio" name="layout" id="radio" value="top,chenter,footer">
    <img src="../image/default/1cl.gif" width="24" height="28">
    
    <input <?php if($de['layout']=='top,left,chenter,footer'){ echo 'checked';}?> type="radio" class="radio" name="layout" id="radio" value="top,left,chenter,footer">
    <img src="../image/default/2cll.gif" width="24" height="28">
    
    <input <?php if($de['layout']=='top,right,chenter,footer'){ echo 'checked';}?> type="radio" class="radio" name="layout" id="radio" value="top,right,chenter,footer">
    <img src="../image/default/2clr.gif" width="24" height="28">
    
    <input <?php if($de['layout']=='top,left,right,chenter,footer'){ echo 'checked';}?> type="radio" class="radio" name="layout" id="radio" value="top,left,right,chenter,footer">
    <img src="../image/default/3cl.gif" width="24" height="28">    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="btn" type="submit" name="submit" id="submit" value="提交"></td>
  </tr>
</table>

	</div>
</div>
</form>
</body>
</html>