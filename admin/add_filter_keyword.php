<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);

@include_once("auth.php");
//====================================================
if($_POST['action']=='submit'&&!empty($_POST['keyword'])&&!empty($_GET['id']))
{
	
	if($_POST['statu']==1)
		$_POST['replace']='';
	$sql="update ".FILTER." set
	keyword='$_POST[keyword]',`replace`='$_POST[replace]',statu='$_POST[statu]' where id='$_GET[id]'";
	$db->query($sql);
	update_cache_filter();
	admin_msg('filter_keyword_list.php','更新成功');
}

if($_POST['action']=='submit'&&!empty($_POST['keyword'])&&empty($_GET['id']))
{
	$time=time();
	$_POST['statu']=empty($_POST['statu'])?0:1;
	foreach(explode("\r\n",$_POST['keyword']) as $v)
	{
		$word=explode('=',$v);
		if($_POST['statu']==1)
			$word[1]='';
		if($_POST['statu']!=1&&empty($word[1]))
			$word[1]='*';
		$sql="insert into ".FILTER." (keyword,`replace`,statu,`time`) values
		 ('$word[0]','$word[1]','$_POST[statu]','$time')";
		$db->query($sql);
	}
	update_cache_filter();
	admin_msg('filter_keyword_list.php','发布成功');
}
function update_cache_filter()
{
	global $db;
	$sql="select * from ".FILTER;
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		if($v['statu']==1)
		{	
			if(empty($banned))
				$banned="$v[keyword]";
			else
				$banned.="|$v[keyword]";
		}
		else
		{
			if(empty($find))
			{
				$find="'/$v[keyword]/i'";
				$replace="'$v[replace]'";
			}
			else
			{
				$find.=",'/$v[keyword]/i'";
				$replace.=",'$v[replace]'";
			}
		}
	}
	if(strlen($banned)>0)
		$banned='/('.$banned.')/i';
	else
		$banned='';
	$str='<?php
	$find= array('.$find.');
	$replace=array('.$replace.');
	$banned=\''.$banned.'\';
	$_CACHE[\'word_filter\'] = Array
	(
		\'filter\' => Array
		(
			\'find\' => &$find,
			\'replace\' => &$replace
		),
		\'banned\' => &$banned
	);
	?>';
	$fp=fopen('../config/filter.inc.php','w');
	fwrite($fp,$str,strlen($str));//将内容写入文件．
	fclose($fp);
}
if(!empty($_GET['id']))
{
	$sql="select * from ".FILTER." where id='$_GET[id]'";
	$db->query($sql);
	$de=$db->fetchRow();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>系统后台</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<form method="post" action="" enctype="multipart/form-data" onSubmit="return checkval(this)">
<div class="bigbox">
	<div class="bigboxhead">词语过滤</div>
	<div class="bigboxbody">
	  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">	
        <tr>
          <td align="left" colspan="2">
		  <?php if(empty($_GET['id'])){ ?>
            <textarea name="keyword" cols="80" rows="20" id="keyword"></textarea>
            <br>
            <?php }else { ?>词组
            <input value="<?php echo $de['keyword'];?>" name="keyword" type="text" size="50">
		    <br>
			替换
			<input name="replace" value="<?php echo $de['replace'];?>" size="50" type="text">
		    <?php } ?>
			</td>
          <td align="left" valign="top" >
		  <?php if(empty($_GET['id'])){ ?>
              <li>每行一组过滤词语和替换词语之间使用“=”进行分割；</li>
              <li>如果只是想将某个词语直接替换成 **，则只输入词语即可；</li>
              <li>例如：<br>
                toobad<br>
                nobad<br>
                badword=good
			</li>
			<?php } else {?>
			<li>每行一组过滤词语，不良词语和替换词语之间使用“=”进行分割；</li>
			<?php } ?>
          </ul>
		  </td>
        </tr>
        <tr>
          <td width="38">
          <input name="statu" type="checkbox" class="checkbox" <?php if($de['statu']==1) echo 'checked';?> id="statu" value="1" ></label></td><td width="398">
            如果包含以上关键词阻止发布
          </td>
          <td width="479"></td>
        </tr>
        <tr>
          <td colspan="3" >
		  <input class="btn" type="submit" name="cc" value="提交">
		  <input name="action" type="hidden" value="submit">          </td>
        </tr>
      </table>
	</div>
</div> 
</form>
</body>
</html>