<?php
include_once("$config[webroot]/module/".$_GET['m']."/includes/news_function.php");
//--------------------ajax check title
if($_GET['check_repeat']=='repeat'&&!empty($_GET['title']))
{
	$sql="select title from ".NEWSD." where title='".urldecode($_GET['title'])."'";
	$db->query($sql);
	$title=$db->fetchField('title');
	if(!empty($title))
		echo 1;
	die;
}
if($_GET['delpic']==1)
{
	$sql="update ".NEWSD." set titlepic='',ispic='0' where nid='$_GET[id]'";
	$re=$db->query($sql);
	if($re)
		echo 1;
	die;
}
//--------------------
if(!empty($_POST['act']))
{
	if(!empty($_POST['img_url']) and empty($pname))
	{
		$pname=$_POST['img_url'];
	}
	
	$imgs_url="";	
	if($_POST['newstempid']==1)
	{
		preg_match_all('/\<img.*?src\=\\\"(.*?)\\\"[^>]*>/i',$_POST['body'],$match);
		$imgs_url=implode('|',$match[1]);
	}
	
	preg_match_all('/\<embed.*?src\=\\\"(.*?)\\\"[^>]*>/i',$_POST['body'],$match);
	$video_url=implode('|',$match[1]);
	
	if(empty($video_url))	
		$video_url="";
	
	$titlefont=implode('|',$_POST['titlefont']);
	
	$time=time();
	if(!empty($_POST['time']))
		$time=strtotime($_POST['time']);

	if(empty($pname))
	   $ispic=0;
	else
	   $ispic=1;
	
	if(empty($_POST['smalltext']))
	{
		$con=$_POST['body'];
		$str = explode('<p>',$con);
		foreach($str as $i=>$k)
		{
			$val=trim(strip_tags($k));
			if(!empty($val))
			{
				$_POST['smalltext']=trim(str_replace("&nbsp;","",$val));
				break;
			}
		}
	}

	if(empty($_POST['closepl']))
		$_POST['closepl']=0;	
	if(empty($_POST['rec']))
		$_POST['rec']=0;
	if(empty($_POST['istop']))
		$_POST['istop']=0;
	if(empty($_POST['pass']))
		$_POST['pass']=0;
	
	@$vote=implode(',',$_POST['vote']).',';
	@$special=implode(',',$_POST['special']).',';
	
	if($_POST['act']=='add')
	{	
		foreach(explode(',',$_POST['type']) as $val)
		{
			$sql="INSERT ".NEWSD."(classid,title,ftitle,keyboard,titleurl,isrec,istop,ispass,onclick,titlefont,uid,uptime,smalltext,writer,source,titlepic,ispic,isgid,ispl,userfen,newstempid,imgs_url,videos_url,vote,special,lastedittime,admin) VALUES ('$val','$_POST[title]','$_POST[ftitle]','$_POST[key]','$_POST[links]','$_POST[rec]','$_POST[istop]','$_POST[pass]','$_POST[onclick]','$titlefont','0','$time','$_POST[smalltext]','$_POST[writer]','$_POST[source]','$pname','$ispic','$_POST[group]','$_POST[closepl]','$_POST[userfen]','$_POST[newstempid]','$imgs_url','$video_url','$vote','$special','".time()."','$_POST[admin]')";
			$re=$db->query($sql);
			$id=$db->lastid();
			$sql="INSERT INTO ".NEWSDATA." (nid,con) values ('$id','$_POST[body]')";
			$re=$db->query($sql);
		}
		msg("module.php?m=news&s=newslist.php&classid=$_GET[type]");
	}
	if($_POST['act']=='edit' and !empty($_GET['newsid']))
	{
		$sql="update ".NEWSD." set title='$_POST[title]',ftitle='$_POST[ftitle]',keyboard='$_POST[key]',titleurl='$_POST[links]', isrec='$_POST[rec]', istop='$_POST[istop]',ispass='$_POST[pass]',onclick='$_POST[onclick]',titlefont='$titlefont',uptime='$time',smalltext='$_POST[smalltext]',writer='$_POST[writer]',source='$_POST[source]',titlepic='$pname',ispic='$ispic',isgid='$_POST[group]',ispl='$_POST[closepl]',userfen='$_POST[userfen]',newstempid='$_POST[newstempid]',imgs_url='$imgs_url',videos_url='$video_url',vote='$vote',admin='$_POST[admin]',special='$special',lastedittime='".time()."' where nid= $_GET[newsid]";
		$re=$db->query($sql);
		$sql="update ".NEWSDATA." set con='$_POST[body]' where nid= $_GET[newsid]";  
		$re=$db->query($sql);
		
		unset($_GET['newsid']);
		unset($_GET['s']);
	    $getstr=implode('&',convert($_GET));
		msg("module.php?m=news&s=newslist.php&$getstr");
	}
}

$sql="select name,user from ".ADMIN." where user='$_SESSION[ADMIN_USER]'";
$db->query($sql);
$ad=$db->fetchRow();
$de['admin']=!empty($ad['name'])?$ad['name']:$ad['user'];

if(!empty($_GET['newsid']))
{
	$sql="select * from ".NEWSD." where nid=".$_GET['newsid'];
	$db->query($sql);
	$de=$db->fetchRow();
	$de['con']=get_newsdata($de['nid']);
	$de['titlefont']=explode('|',$de['titlefont']);
	$col=array_pop($de['titlefont']);
	$de['vote']=explode(',',$de['vote']);
	$de['special']=explode(',',$de['special']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../lib/calendar/calendar.css" >
<style>
.bigboxbody td{ padding-left:5px;}
.bigboxbody select{ width:120px;} 
#colortab td{ padding:0px;}
.radio{ float:left; margin-right:4px;}
.radio { margin-top:5px } 
* html .radio { margin-top:0px } 
*+html .radio { margin-top:0px}
u{ text-decoration:none; float:left; margin-right:6px;}
.sel{ width:515px !important; height:160px; padding:2px 0px;}
.sel .option{  padding:2px 4px !important;}
.sel option{ font-size:14px; height:20px; line-height:20px; padding:2px 6px;  }

</style>
</HEAD>
<script src="../script/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="../script/my_lightbox.js" language="javascript"></script>
<script>
function setTab(name,cursel,n)
{
	for(i=1;i<=n;i++)
	{   var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		menu.className=i==cursel?"current":"tag";
		con.style.display=i==cursel?"block":"none";
	}
}
function setValue(a)
{
    if(document.getElementById('sel'+a).selectedIndex!=0)
		document.getElementById(a).value=document.getElementById('sel'+a).options[document.getElementById('sel'+a).selectedIndex].text;
	else
		document.getElementById(a).value='';
	document.getElementById(a+'id').value=document.getElementById('sel'+a).value
} 

function get_value()
{
	frames['form'].document.forms[0].vname.value
	
}
function checkval(myform)
{
	if(myform.ftitle.value=="")
	{
		alert("标题不能为空!");
		myform.ftitle.focus();
		return (false);
	}
	if(myform.title.value=="")
	{
		alert("副标题不能为空!");
		myform.title.focus();
		return (false);
	}
	if(myform.smalltext.value=="")
	{
		alert("内容简介不能为空!");
		myform.smalltext.focus();
		return (false);
	}
	if(myform.smalltext.value.length>200)
	{
		alert("内容简介不能大于200字!");
		myform.smalltext.focus();
		return (false);
	}
	
}
</script>
<body>
<form method="post" action="" enctype="multipart/form-data" onSubmit="return checkval(this)">
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('news'); ?></div>
	<div class="bigboxbody">
  	<input type="hidden" value="<?php if($_GET['type']) { echo $_GET['type']; } else { echo @implode(',',$_POST['type']); } ?>" name="type">
	<input type="hidden" name="act" value="<?php if(isset($_GET['newsid'])) echo "edit"; else echo "add"; ?>" />
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr height="65">
           <td width="10%" height="22" align="center">标 &nbsp;&nbsp;&nbsp;&nbsp;题</td>
           <td height="22" align="left" valign="middle" colspan="2">
		   <input onblur="check_title(this.value);" type="text" class="input" name="title" id="title" size="60" value="<?php if(!empty($de["title"])) echo $de["title"];?>">&nbsp;<span style="color:#FF0000" id="repeat"></span><br>
           属性：
            <input  type="checkbox" class="checkbox" name="titlefont[]" value="b" <?php if (@in_array('b',$de['titlefont'])) echo 'checked="checked"'; ?> > 粗体
            <input type="checkbox" class="checkbox" name="titlefont[]" value="i" <?php if (@in_array('i',$de['titlefont'])) echo 'checked="checked"'; ?> > 斜体
            <input type="checkbox" class="checkbox" name="titlefont[]" value="s" <?php if (@in_array('s',$de['titlefont'])) echo 'checked="checked"'; ?> > 删除线&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" id="color" name="titlefont[]"  size="18" value="<?php if( $col!='b' and $col!='i' and $col!='s') echo $col; ?>"  style="background-color:<?php if( $col!='b' and $col!='i' and $col!='s') echo $col; else echo "FFFFFF"; ?>" > 颜色
           <div id="colorpane" style="position:absolute;z-index:999;display:none;"></div>
		   </td>
      </tr>
	  <tr>
	      <td align="center">副 标 题</td>
		  <td colspan="2"><input type="text" class="input"  name="ftitle" id="ftitle" size="60" value="<?php if(!empty($de["ftitle"])) echo $de["ftitle"];?>"></td>
	  </tr> 
   
      <tr>
           <td align="center" rowspan="6">特殊属性</td>
           <td width="12%">信息属性：</td>
		   <td>
             <input type="checkbox" class="checkbox" name="rec" id="rec" value="1" <?php if($de["isrec"]==1) echo " checked"; ?> /> 推荐   
             <input type="checkbox" class="checkbox" name="pass" id="pass" value="1"  <?php if($de["ispass"]==1 or !isset($de["ispass"])) echo " checked"; ?>  /> 审核       
             <input type="checkbox" class="checkbox" name="istop" id="istop" value="1" <?php if($de["istop"]==1) echo " checked"; ?>  /> 头条			</td>
        </tr>
       <tr>
           <td>关 键 字：</td>
		   <td><input type="text" class="input"  name="key" id="key" size="48" value="<?php if(!empty($de["keyboard"])) echo $de["keyboard"];?>" />
           <span class="grey"> (多个请用","格开)</span></td>
       </tr>
       <tr>
           <td>外部链接：</td>
		   <td><input type="text"  class="input" name="links" id="links" size="48" value="<?php if(!empty($de["titleurl"])) echo $de["titleurl"];?>" /> </td>
       </tr>
	   
       <!--<tr>
         <td>查看扣除点数：</td>
         <td>-->
         <input type="hidden" name="group" value="0" />
		 <input type="hidden" name="userfen" id="userfen" size="6"  value="<?php if(!empty($de["userfen"])) echo $de["userfen"];else echo "0" ;?>" />       <!--  </td>
       </tr>-->
       
       <tr>
         <td>点 击 数：</td>
         <td><input size="18" type="text" id="onclick" name="onclick" value="<?php if(!empty($de["onclick"])) echo $de["onclick"]; else echo "0" ;?>" />&nbsp;&nbsp;&nbsp;&nbsp;  
           关闭评论： <input type="checkbox" class="checkbox" value="1" name="closepl" <?php if($de["ispl"]==1) echo " checked"; ?> /></td>
       </tr>
        <tr>
       		<td>选择投票： <a href="module.php?m=vote&s=vote.php">+</a></td>
      		<td>
            	<?php 
					$sql="select id,title from ".NEWSVOTE." order by id asc";	
					$db->query($sql);
					$re=$db->getRows();
					foreach($re as $val)
					{   
						if(is_array($de['vote']))	
						{
							if(in_array($val['id'],$de['vote']))
								$str="checked";
							else
								$str="";
						}
						echo "<input $str type='checkbox' class='radio' name='vote[]' id='vote' value='$val[id]' /><u>$val[title]</u>";
					}
				?>              </td> 
        </tr>
       
	   <tr>
           <td align="center">发布时间</td>
           <td colspan="2">
		     <input type="text" class="input"  name="time" id="time" size="20" value="<?php if(!empty($de["uptime"])) echo date('Y-m-d H:i:s',$de["uptime"]); ?>" />
			 <input class="btn" value="设为当前时间" type="button" onClick="document.getElementById('time').value='<?php echo date("Y-m-d H:i:s") ?>'">		   </td>
       </tr> 
	          <tr>
           <td align="center">作 &nbsp;&nbsp;&nbsp;&nbsp;者</td>
           <td colspan="2">
           <input type="text" class="input" name="writer" id="writer" value="<?php if(!empty($de["writer"])) echo $de["writer"]; ?>" />		   </td>
        </tr> 
        <tr>
           <td align="center">来 &nbsp;&nbsp;&nbsp;&nbsp;源</td>
           <td colspan="2">
           <input type="text" class="input"  name="source" id="source" value="<?php if(!empty($de["source"])) echo $de["source"]; ?>">		   </td>
        </tr>
		<tr>
                <td align="center">责任编辑</td>
                <td colspan="2"><input type="text" class="input"  name="admin" id="admin" value="<?php if(!empty($de["admin"])) echo $de["admin"]; ?>">	</td>
       </tr>
       <tr>
           <td align="center">标题图片</td>
           <td colspan="2">
           	 <?php /*?><?php if(!empty($de["titlepic"])) { ?> 
             <span id='news_pic'>
			 <img src="<?php echo $config['weburl'] ?>/uploadfile/news/<?php echo $de["titlepic"]?>"><br>
			 <input type="hidden" name="pic" value="<?php echo $de["titlepic"]; ?> ">
			 <a href="javascript:del_pic(<?php echo $_GET['newsid'];?>);">删除图片</a><br>
			 </span> 
			 <?php }  ?>
           	  <input type="file" id="img_url" name="img_url" size="21" ><?php */?>
              
<input name="img_url" class="w350" type="text" id="img_url" value="<?php echo $de["titlepic"]?>" />
<input name="oldpic" type="hidden" value="<?php echo $de["titlepic"]?>" />
[<a href="javascript:uploadfile('上传LOGO','img_url',275,200,'news')">上传</a>] 
[<a href="javascript:preview('img_url');">预览</a>]
[<a onclick="javascript:$('#img_url').val('');" href="#">删除</a>]
			 </td>
        </tr>
       <tr>
           <td align="center">内容简介</td>
           <td colspan="2">
           <textarea maxlength="100" name="smalltext" id="smalltext" rows="3" cols="100"><?php if(!empty($de["smalltext"])) echo $de["smalltext"]; ?></textarea>           </td>
        </tr> 
          
      
        <tr>
           <td align="center">内容模板</td>
           <td colspan="2">
            <select name="newstempid" id="newstempid" style="float:left; ">
            <?php 
             foreach(lang_show('newstempid') as $key=>$val)
             {
			   if($key==$de['newstempid'])
			   	$sl=" selected='selected";
			   else
			   	$sl=NULL;
               echo "<option $sl value='".($key)."'>".$val."</option>";
             }
            ?>
            </select> <div style="float:left; ">*图片模版 使用</div><div style="background-position: 0 -1024px; background-image: url(<?php echo $config['weburl'] ?>/lib/kindeditor/themes/default/default.png); height:16px; width:16px; margin:5px 1px 0; border:none; float:left; "></div><div style="float:left; ">(分页符) 分开 图片</div></td>
        </tr>
        <tr><td align="center">新闻正文</td><td colspan="2">
           
		   <script charset="utf-8" src="../lib/kindeditor/kindeditor-min.js"></script>             
			<script>
            var editor;
            KindEditor.ready(function(K) {
                editor = K.create('textarea[name="body"]', {
                    resizeType : 1,
                    allowPreviewEmoticons : false,
                    allowImageUpload : false,
                    langType :'<?php echo $config['language']; ?>',
                });
            });
            </script>
            <textarea name="body" style="width:90%; height:400px;"><?php echo $de["con"] ?></textarea>
            
           </td>
        </tr>
        
        <tr>
          <td></td>
          <td colspan="2">
              <input name="aid" type="hidden" id="aid" value="">
              <input name="action" type="hidden" id="action" value="<?php if(!empty($_GET["id"])) echo "edit";else echo "submit"; ?>">
              <input class="btn" type="submit" id="btn" name="submit" value="<?php if(!isset($_GET['newsid'] )) echo "提交"; else echo "修改" ?>">&nbsp;
              <input class="btn" type="reset" value="重置">          </td>
        </tr> 
    </table>
    </div>
</div>
</form>
</body>
<script>
function check_title(title)
{	
	if(title!='')
	{
		var url = 'module.php?m=news&s=news.php';
		var pars ='title='+encodeURI(title)+'&check_repeat=repeat'; 
		$.get(url, pars,
		function (originalRequest)
			{
				if(originalRequest==1)
					$('#repeat').html('标题重复');
				else
					$('#repeat').html('无重复标题');
				
			});
	}
}

function del_pic(id)
{
    var url = 'module.php?m=news&s=news.php';
	var pars ='id='+id+'&delpic=1'; 
	$.get(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest==1)
			$('#news_pic').html('');
		
    }
}
</script>
</html>