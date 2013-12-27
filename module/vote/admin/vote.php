<?php
if($_POST['act'] == 'add')
{
	$name=$_POST['vote_name']; 
	$num=$_POST['vote_num'];
	$_POST['vtime']=empty($_POST['vtime'])?date("Y-m-d"):$_POST['vtime'];
	
	for($i=0;$i<$_POST['tdnum'];$i++)
	{ 
		 if(!empty($name[$i]))
		 {
			 $count+=$num[$i];
			 if(empty($votetext))
				$votetext=$name[$i].','.$num[$i];
			 else
				$votetext.="|".$name[$i].','.$num[$i];
		 }
	}
	$sql="INSERT ".NEWSVOTE." (title,votetext,votetype,num,limitip,time,uptime) 
	VALUES('$_POST[vname]','$votetext','$_POST[vtype]','$count','$_POST[vlimit]','$_POST[vtime]',".time().")";
	$re=$db->query($sql);
	msg("module.php?m=vote&s=vote_list.php");
}

if($_POST['act']== 'edit' and !empty($_GET['vid']))
{
	$name=$_POST['vote_name']; 
	$num=$_POST['vote_num']; 
	$_POST['vtime']=empty($_POST['vtime'])?date("Y-m-d"):$_POST['vtime'];
	
	for($i=0;$i<$_POST['tdnum'];$i++)
	{ 
		if(!empty($name[$i]))
		{
			$count+=$num[$i];
			if(empty($votetext))
				$votetext=$name[$i].','.$num[$i];
			else
				$votetext.="|".$name[$i].','.$num[$i];
		}
	}
	$sql="update ".NEWSVOTE."  set 
	title='$_POST[vname]',votetext='$votetext',votetype='$_POST[vtype]',
	num='$count',limitip='$_POST[vlimit]',time='$_POST[vtime]' where id=".$_GET['vid'];
	$re=$db->query($sql);
	msg("module.php?m=vote&s=vote_list.php");
}

if(!empty($_GET['vid']))
{
	$sql="select * from ".NEWSVOTE." where id=$_GET[vid]";	
	$db->query($sql);
	$re=$db->fetchRow();
	$vote=explode('|',$re['votetext']);
	for($i=0;$i<count($vote);$i++)
	{
		$vote[$i]=explode(',',$vote[$i]);
	}
}
?>
<link href="main.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../script/Calendar.js"></script>
<script>
function add(a)
{
	var length=document.getElementById(a).rows.length;
	if(length<12){
		document.getElementById('tdnum').value=length-1;
		document.getElementById(a).insertRow(length-1);
		for(i=1; i<4; i++)
		{
			var cell=document.getElementById(a).rows[length-1].insertCell(0);		
			if(i==3)
			{
				cell.align='center';
				cell.innerHTML=length-1;
			}
			else if(i==2)
			cell.innerHTML="<input type='text' name='vote_name[]' size='40'>";
			else 
			cell.innerHTML="<input type='text' name='vote_num[]' size='6' value='0'>";
		}
		
	}
	else
	{
	 	alert("最多只能10个");	
	}

}	
</script>
<div class="bigbox">
<div class="bigboxhead"><?php echo lang_show('vote'); ?></div>
<div class="bigboxbody">
<form action="" method="post">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr class="tr">
	   <td class="td" width="12%" align="center"><?php echo lang_show('votename'); ?></td>
	   <td class="td"><input type="text" name="vname" value="<?php echo $re['title']; ?>" id="vname" size="50"></td>
	</tr>
	<tr class="tr">
	   <td class="td" align="center" valign="top"><?php echo lang_show('voteitem'); ?></td>
	   <td class="td">
       	   <input type="hidden" name="tdnum" id="tdnum" value="<?php if(isset($vote)) echo count($vote); else echo '3' ?>" />
	       <table cellpadding="0" cellspacing="0" width="80%" border="0" class="vote" id="vote">
		      <tr class="tr theader">
			     <td class="td" width="8%" align="center">编号</td><td width="30%" class="td">项目名称</td><td class="td">投票数</td>
			  </tr>
			  <?php
			    if(!empty($vote))
				{  
				  foreach($vote as $key=>$val)
			      {
			  ?>  
			  <tr class="tr"> 
				  <td class="td" align="center"><?php echo $key+1 ?></td>
				  <td class="td"><input value="<?php echo $val[0]; ?>" type="text" size="40" name="vote_name[]"></td>
				  <td class="td"><input type="text"  value="<?php echo $val[1]; ?>" size="6" value="0" name="vote_num[]"></td>
			  </tr>
			    
			  <?php
			      }
				}
				else{ 
			  ?>
			  <tr class="tr"> 
				  <td class="td" align="center">1</td>
				  <td class="td"><input type="text" size="40" name="vote_name[]"></td>
				  <td class="td"><input type="text" size="6" value="0" name="vote_num[]"></td>
			  </tr>
			  <tr class="tr"> 
				  <td class="td" align="center">2</td>
				  <td class="td"><input type="text" size="40" name="vote_name[]"></td>
				  <td class="td"><input type="text" size="6" value="0" name="vote_num[]"></td>
			  </tr>
              <tr class="tr"> 
				  <td class="td" align="center">3</td>
				  <td class="td"><input type="text" size="40" name="vote_name[]"></td>
				  <td class="td"><input type="text" size="6" value="0" name="vote_num[]"></td>
			  </tr>
             <?php
			     }
			  ?>
              <tr class="tr">
              	<td class="td" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn" type="button" value="添加一行" onclick="add('vote')" /></td>
              	<td class="td">总票数:<?php if(!empty($re['num'])) echo $re['num']; else echo '0'; ?></td>
              </tr>  
		   </table>
        </td>
	</tr>
	 <tr class="tr">
	   <td class="td" align="center"><?php echo lang_show('votetype'); ?></td>
	   <td class="td">
	       <?php
			foreach(lang_show('votetype_arr') as $key=>$val)
			{
			  if($re['votetype']==$key)
			  	$str="checked='checked'";
			  else
			 	 $str="";
			  echo "<input $str type='radio' class='radio' id='vtype' name='vtype' value='".$key."'><u>".$val."</u>";
			}
		   ?>
		 </td>
	</tr>
	 <tr class="tr">
	   <td class="td" align="center"><?php echo lang_show('votelimit'); ?></td>
	   <td class="td">
	       <?php 
			foreach(lang_show('votelimit_arr') as $key=>$val)
			{
			  if($re['limitip']==$key)
			  	$str="checked='checked'";
			  else
			 	 $str="";
			  echo "<input $str type='radio' class='radio' id='vlimit' name='vlimit' value='".$key."'><u>".$val."</u>";
			}
			echo lang_show('votelimit_show');
		   ?>
	   </td>
	</tr>
	<tr class="tr">
	   <td class="td" align="center"><?php echo lang_show('votetime'); ?></td>
	   <td class="td">
       <script language="javascript">
			var cdr = new Calendar("cdr");
			document.write(cdr);
			cdr.showMoreDay = true;
			</script>
	     <input type="text" name="vtime" id="vtime" value="<?php if($re['time']!='0000-00-00'){ echo $re['time'];} ?>" onfocus="cdr.show(this);" > <?php echo lang_show('votetime_show'); ?>
	   </td>
	</tr>  
	<tr class="tr">
	   <td class="td"></td>
	   <td class="td"><input class="btn" type="submit" name="submit" id="submit" value="<?php if(!isset($_GET['vid'])) echo lang_show('addvote'); else  echo lang_show('editvote'); ?>">
       	<input type="hidden" name="act" value="<?php if(!isset($_GET['vid']))echo'add'; else  echo'edit';?>" />
	   </td>
	</tr>
	</table>  
</form>  
</div>
</div>