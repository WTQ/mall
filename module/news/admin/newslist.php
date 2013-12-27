<?php
include_once("../includes/page_utf_class.php");
include_once("../module/".$_GET['m']."/includes/news_function.php");
//===================================================
$submit=isset($_GET['submit'])?$_GET['submit']:NULL;
@$id=implode(",",$_GET['chk']);
if(!empty($_GET['did']))
{
	$id=$_GET['did'];
}

if(!empty($id))
{
	if($submit==lang_show('del') or !empty($_GET['did']))
	{
	   $sql="delete from ".NEWSD." where nid in ($id)";
	   $db->query($sql);
	   $sql="delete from ".NEWSDATA." where nid in ($id)";
	}
	if($submit==lang_show('bres'))
	   $sql="update ".NEWSD." set isrec=1 where nid in ($id)";
	if($submit==lang_show('nbres'))
	   $sql="update ".NEWSD." set isrec=0 where nid in ($id)";
	if($submit==lang_show('bpass'))
	   $sql="update ".NEWSD." set ispass=1 where nid in ($id)";
	if($submit==lang_show('nbpass'))
	   $sql="update ".NEWSD." set ispass=0 where nid in ($id)";
	if($submit==lang_show('bhead'))
	   $sql="update ".NEWSD." set istop=1 where nid in ($id)";
	if($submit==lang_show('nbhead'))
	   $sql="update ".NEWSD." set istop=0 where nid in ($id)";
	if($submit==lang_show('move') and !empty($_GET['nclass']))
	   $sql="update ".NEWSD." set classid=$_GET[nclass] where nid in ($id)";
    if(!empty($sql))
		$db->query($sql);
	
	if($submit==lang_show('copy') and !empty($_GET['nclass']))
	{ 
		foreach($_GET['chk'] as $val)
		{
			$sql="INSERT ".NEWSD."(title,ftitle,keyboard,titleurl,isrec,istop,ispass,firsttitle,onclick,titlefont,uid,uptime,smalltext,writer,source,titlepic,ispic,isgid,ispl,userfen,newstempid,pagenum,imgs_url,videos_url,vote,special,classid,lastedittime) select title,ftitle,keyboard,titleurl,isrec,istop,ispass,firsttitle,onclick,titlefont,uid,uptime,smalltext,writer,source,titlepic,ispic,isgid,ispl,userfen,newstempid,pagenum,imgs_url,videos_url,vote,special,'$_GET[nclass]',lastedittime from ".NEWSD." where nid =$val";
			$db->query($sql);
			$id=$db->lastid();
			$sql="INSERT INTO ".NEWSDATA." (con,nid) select con,$id from ".NEWSDATA." where nid =$val";
			$re=$db->query($sql);
		}
	}
}
if($submit==lang_show('search'))
{
	//unset($_GET['totalRows']);
	//unset($_GET['firstRow']);因为按分类搜索后不能翻页,所以删除了.可能其它地方有用,到时候再查
	$type=$_GET['seltype'];
	$field=$_GET['field'];
	$key=trim($_GET['key']);
	if($type==1)
	   $str.=" and isrec=1";
	if($type==2)
	   $str.=" and istop=1";
	if($type==3)
	   $str.=" and ispass=0";
	if($type==4)
	   $str.=" and ispass=1";
	if($type==5)
	   $str.=" and uid!=0";
	if(!empty($key))
	{
		if($field==0)
		   $str.=" and (title like '%$key%' or ftitle like '%$key%' or smalltext like '%$key%' or user like '%$key%' or nid like '%$key%')";
		if($field==1)
		   $str.=" and title like '%$key%'";
		if($field==2)
		   $str.=" and ftitle like '%$key%'";
		if($field==3)
	       $str.=" and smalltext like '%$key%'";
		if($field==4)
		   $str.=" and user like '%$key%'";
		if($field==5)
		   $str.=" and nid like '%$key%'";
	}
	if(!empty($_GET['classid']))
	{
		 $cid=get_lowerid($_GET['classid']);
		 $str.=" and a.classid in ($cid) ";		
	}
}
if(!empty($_GET['classid']))
{
	 $cid=get_lowerid($_GET['classid']);
	 $str.=" and a.classid in ($cid,$_GET[classid]) ";	
}

if($_GET['o']==1)
	$order=" order by nid";
else if($_GET['o']==2)
	$order=" order by onclick DESC";
else if($_GET['o']==3)
	$order=" order by onclick";
else if($_GET['o']==4)
	$order=" order by nid DESC";
else if($_GET['o']==5)
	$order=" order by nid";
else if($_GET['o']==6)
	$order=" order by uptime DESC";
else if($_GET['o']==7)
	$order=" order by a.uptime";	
else
	$order=" order by nid DESC";

$sql="select nid,ftitle,title,uid,onclick,a.uptime,isrec,istop,classid,user,ispic,ispass,admin
     from ".NEWSD." a left join ".ALLUSER." on userid=uid where 1 $str ".$order;
//---------------------
$page = new Page;
$page->listRows=20;
if (!$page->__get('totalRows')){
	$db->query("select count(*) as total from ".NEWSD." a left join ".ALLUSER." on userid=uid where 1 $str ".$order);
	$page->totalRows = $db->fetchField('total');
}
$sql .= "  limit ".$page->firstRow.",".$page->listRows;
$pages = $page->prompt();
//------------------------
$db->query($sql);
$re=$db->getRows();
unset($_GET['s']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />

<style>
.bigboxbody{ border:none !important;}
.bigboxbody td{ padding-left:5px; border:0px; text-align:center}
.bigboxbody .v{ text-align:left;}
.bigboxbody .r{ text-align:right; padding-right:1%;}
.bigboxbody .v span{ color:#FF0000;}
.bigboxbody .v a{ text-decoration:none }
.bigboxbody select{ width:120px;} 
.bigbox tr{ background:#FFFFFF}
.class_2{background:#fefbc6 !important;cursor:hand;}
.class_3{background:#FFCC99 !important;cursor:hand;} 
</style>
</HEAD>
<script src="<?php echo $config['weburl']; ?>/script/jquery-1.4.4.min.js" type="text/javascript"></script>
<script language="javascript">
function do_select()
{
	 var box_l = document.getElementsByName("chk[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("chk[]")[j].checked==true)
	  	{
			document.getElementsByName("chk[]")[j].checked = false;
			document.getElementById('tr'+j).className='class_1';
		}
		else
		{
			document.getElementsByName("chk[]")[j].checked = true;
			document.getElementById('tr'+j).className='class_3';
		}
	 }
}
</script>

<body>

<div class="bigbox">
	<div class="bigboxhead"><?php if(isset($_GET['classid']) and !empty($_GET['classid']) ) { echo get_cat($_GET['classid']);} else { echo lang_show('news'); } ?></div>
	<div class="bigboxbody">
	<form method="get" action="">
	<input name="m" type="hidden" value="news" />
	<input name="s" type="hidden" value="newslist.php" />
	   <table border="0" cellpadding="2" cellspacing="1" width="100%" bgcolor="#dddddd">
          <tr>
		     <td colspan="2">
			 	<input class="btn" type="button" value="<?php echo lang_show('add'); ?>" name="add" id="add" onClick="window.location.href='<?php if(!empty($_GET['classid'])) { echo "module.php?m=news&s=news.php&type=$_GET[classid]"; } else { echo "module.php?m=news&s=news_step.php"; } ?>'">
			 </td>
			 <td colspan="6" class="r">
			 <?php echo lang_show('search');?>：
			  <select name="seltype" id="seltype" style="width:80px;">
			   <?php 
			   	  foreach(lang_show('seltype') as $key=>$val)
			   	  {
				    if($_GET['seltype']==$key)
					   $selected="selected='selected'";
					else
					   $selected="";
					echo "<option $selected value='".$key."'>".$val."</option>";
				  }
			   ?>
			  </select>
			  <input type="text" name="key" id="key" size="30" value="<?php echo $_GET['key']; ?>">
			  <select name="field" id="field" style="width:80px;">
			   <?php 
			   	  foreach(lang_show('field') as $key=>$val)
			   	  {
					if($_GET['field']==$key)
					   $selected="selected='selected'";
					else
					   $selected="";
				    echo "<option $selected  value='".$key."'>".$val."</option>";
				  }
			   ?>
			  </select>
              
              <select name="classid" id="classid" style="width:100px;">
              	 <option value="">所有栏目</option>
                 <?php
				 	if(empty($_GET['classid']) and !empty($_GET['classid']))
				 	{
						$_GET['classid']=$_GET['classid'];
					}
                    foreach(get_newscat() as $key=>$val)
                    {
						if($val['catid']==$_GET['classid'])
							$sel='selected';
						else
							$sel='';
							
						echo "<option $str $sel value='".$val['catid']."'>|-".$val['cat']."</option>";
						foreach($val['subcat'] as $keys=>$vals)
						{
							if($vals['catid']==$_GET['classid'])
								$sel='selected';
							else
								$sel='';
								
							echo "<option $str $sel class='op1' value='".$vals['catid']."'>|-|-".$vals['cat']."</option>";		
							foreach($vals['subscat'] as $keys=>$list)
							{
								
								if($list['catid']==$_GET['classid'])
									$sel='selected';
								else
									$sel='';
									
								echo "<option $str $sel class='op2' value='".$list['catid']."'>|-|-|-".$list['cat']."</option>";			
								foreach($list['subscat'] as $keys=>$lists)
								{
									if($lists['catid']==$_GET['classid'])
										$sel='selected';
									else
										$sel='';
									echo "<option $sel class='op3' value='".$lists['catid']."'>|-|-|-|-".$lists['cat']."</option>";
								}
							}
						}
                    }
                    ?> 
              </select>
          	  <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('search') ?>" />
			 </td>
		  </tr>
          
          
		  <tr class="theader">
		     <td colspan="2"><a href="module.php?m=news&s=newslist.php&classid=<?php echo $_GET['classid']; ?>&o=<?php if($_GET['o']==1) echo ""; else echo "1"; ?>"><?php echo lang_show('id'); ?></a></td>
			 <td><?php echo lang_show('title'); ?></td>
             <?php if(empty($_GET['classid'])) { ?>
             <td><?php echo lang_show('class'); ?></td>
             <?php } ?>
			 <td><?php echo lang_show('publishers'); ?></td>
			 <td><a href="module.php?m=news&s=newslist.php&classid=<?php echo $_GET['classid']; ?>&o=<?php if($_GET['o']==3) echo "2"; else echo "3"; ?>"><?php echo lang_show('click'); ?></a></td>
			 <td><a href="module.php?m=news&s=newslist.php&classid=<?php echo $_GET['classid']; ?>&o=<?php if($_GET['o']==7) echo "6"; else echo "7"; ?>"><?php echo lang_show('time'); ?></a></td>
			<td><?php echo lang_show('operation'); ?></td>
		  </tr>
		
        
        
		  <?php foreach($re as $key=>$val) { ?>
		  <tr id="tr<?php echo $key;?>" onMouseOver="this.className='class_2'" onMouseOut="if(document.getElementById('chk<?php echo $key ?>').checked) this.className='class_3'; else this.className='class_1'" >
		     <td><input type="checkbox" class="checkbox" name="chk[]" id="chk<?php echo $key ?>" value="<?php echo $val['nid']?>" /></td>
			 <td><?php echo $val['nid'] ?></td>
			 <td class="v">
			 	<span>
                <?php if($val['ispass']==0) { ?><img align="absmiddle" src="../image/default/off.gif" /><?php } ?>
				<?php if($val['isrec']==1) echo '['.lang_show('rec').']';?>
				<?php if($val['istop']==1) echo '['.lang_show('top').']';?>
				<?php if($val['ispic']==1) echo '<img align="absmiddle" src="../image/default/image_s.gif" />'; ?>
                </span>
                <a href="<?php echo $config['weburl'] ?>/?m=news&s=newsd&id=<?php echo $val['nid']; ?>" title="<?php echo $val['title'] ?>" target="_blank" ><?php echo $val['title'] ?></a>
                
			 </td>
             <?php if(empty($_GET['classid'])) { ?>
             <td><a href="<?php echo $config['weburl'] ?>/admin/module.php?m=news&s=newslist.php&classid=<?php echo $val['classid'];?>"  ><?php echo get_cat($val['classid']); ?></a></td>
             <?php }  ?>
			 <td><?php if($val['uid']==0) echo $val['admin'];  else echo "<font color='#FF0000'>".$val['user']."</font>"; ?></td>
		     <td><?php echo $val['onclick'] ?></td>
			 <td><?php echo date('Y-m-d',$val['uptime']); ?></td>
			 <td>
			 	<a href="module.php?m=news&s=news.php&newsid=<?php echo $val['nid'].'&'.implode('&',convert($_GET));?>"><img src="<?php echo $config['weburl']; ?>/image/admin/edit.gif"></a>
			    <a onClick="return confirm('确认要删除？');" href="module.php?m=news&s=newslist.php&did=<?php echo $val['nid'];?>"><img src="<?php echo $config['weburl']; ?>/image/admin/del.gif"></a>
			 </td>
		  </tr>
		  <?php } ?>
		  
          
          
		  <tr>
		    <td ><input type="checkbox" class="checkbox" onClick="do_select()" name="chkall" id="chkall" > </td>
		    <td colspan="3" class="v">
            <input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('del') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('bres') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('nbres') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('bpass') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('nbpass') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('bhead') ?>" />
			<input class="btn" type="submit" name="submit" id="submit" value="<?php echo lang_show('nbhead') ?>" />
			</td>
            <td colspan="4">
            	<select name="nclass" id="nclass" style="width:160px;">
              	 <option value="">选择要移动/复制的栏目</option>
                  <?php
                    $str1="disabled='disabled'";
                    foreach(get_newscat() as $key=>$val)
                    {
						if(empty($val['subcat']))
							$str='';
						else
							$str=$str1;
						
						echo "<option $str $sel value='".$val['catid']."'>|-".$val['cat']."</option>";
						foreach($val['subcat'] as $keys=>$vals)
						{
							if(empty($vals['subscat']))
								$str='';
							else
								$str=$str1;
							echo "<option $str $sel class='op1' value='".$vals['catid']."'>|-".$vals['cat']."</option>";		
							foreach($vals['subscat'] as $keys=>$list)
							{
								if(empty($list['subscat']))
									$str='';
								else
									$str=$sstr1;
								echo "<option $str $sel class='op2' value='".$list['catid']."'>|-".$list['cat']."</option>";			
								foreach($list['subscat'] as $keys=>$lists)
								{
									echo "<option $sel class='op3' value='".$lists['catid']."'>|-".$lists['cat']."</option>";
								}
							}
						}
                    }
                    ?> 
                </select>
                <input class="btn" type="submit" name="submit" onClick="return confirm('确认要执行此操作？');" value="<?php echo lang_show('copy') ?>" />
                <input class="btn" type="submit" name="submit" onClick="return confirm('确认要执行此操作？');" value="<?php echo lang_show('move') ?>" />
            </td>
	     </tr>
         <tr>
         	<td colspan="8" class="r"><div class="page"><?php echo $pages?></div></td>
         </tr>
	   </table>
  </form> 
	</div>
</div>

</body>
</html>
