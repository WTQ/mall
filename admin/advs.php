<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
$db->query("select `group` from ".ADVS." group by `group`");
$group=$db->getRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('adv_menager');?></div>
	<div class="bigboxbody">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="theader"> 
      <td width="50" align="center"><?php echo lang_show('number');?></td>
      <td width="182" align="left">名称</td>
      <td width="182" align="left"><select onchange="window.location='advs.php?group='+this.value;" name="">
	  <option value="">所有广告</option>
	  <?php
	  foreach($group as $v)
	  { 
		  if($v['group'])
		  {
		  	if($_GET['group']==$v['group'])
				$sl='selected="selected"';
			else
				$sl=NULL;
			echo '<option value="'.$v['group'].'" '.$sl.'>'.$v['group'].'</option>';
		  }
	  }?>
	  </select></td>
      <td width="71" align="left"><?php echo lang_show('adv_type');?></td>
      <td width="81" align="left">价格</td>
      <td width="77" align="left">规格</td>
      <td width="73" align="left">展示次数</td>
      <td width="133" align="left"><?php echo lang_show('include_code');?></td>
      <td width="97" align="center"><?php echo lang_show('operate');?></td>
    </tr>
    <?php
	$adt[1]=lang_show('text');
	$adt[2]=lang_show('code');
	$adt[3]=lang_show('image');
	$adt[4]=lang_show('flash');
	if(!empty($_GET['group']))
		$sql=" and `group`='$_GET[group]'";
	else
		$sql=NULL;
	$db->query("select * from ".ADVS." where 1 $sql order by ID asc");
	$re=$db->getRows();
	for($i=0;$i<count($re);$i++)
	{
		$sql="select count(*) as num,sum(shownum) as shownum from ".ADVSCON." WHERE isopen=1 and group_id='".$re[$i]['ID']."'";
		$db->query($sql);
		$num=$db->fetchRow();
	?>
    <form name="form1" method="post" action="">
      <tr> 
        <td align="center"><?php echo $re[$i]['ID']; ?></td>
        <td align="left">
		<a <?php if($num['num']==0) echo 'style="color:#999999"';?> href="advs_con_list.php?group_id=<?php echo $re[$i]['ID']; ?>&group=<?php echo $_GET['group'];?>">
			<?php echo $re[$i]['name'];?>(<?php echo $num['num']; ?>)
		</a>
		</td>
        <td align="left"><?php echo $re[$i]['group'];?></td>
        <td align="left">
			<?php 
			if(!empty($re[$i]['ad_type'])&&$re[$i]['ad_type']==1)
				echo '普通广告位';
			elseif($re[$i]['ad_type']==2)
				echo '幻灯片集合';
			else
				echo '对联广告位';
			?>		</td>
        <td align="left"><?php echo $config['money'].$re[$i]['price'];?></td>
        <td align="left"><?php echo $re[$i]['width'];?>*<?php echo $re[$i]['height'];?></td>
        <td align="left"><?php echo $num['shownum']; ?>&nbsp;</td>
        <td align="left">
		
		<input name="js" type="text" id="js" value="<script src='&lt;{$config.weburl}&gt;/api/ad.php?id=<?php echo $re[$i]['ID']; ?>&catid=&lt;{$smarty.get.id}&gt&name=&lt;{$smarty.get.key}&gt'></script>" size="25">
		</td>
        <td align="center">
			<a href="edit_adv.php?id=<?php echo $re[$i]['ID'];?>"><?php echo $editimg;?></a>
			<a title='<?php echo lang_show('del');?>' onclick='return confirm("<?php echo $lang['sure_dele'];?>")' href="edit_adv.php?action=dele&id=<?php echo $re[$i]['ID'];?>&group=<?php echo $_GET['group'];?>"><?php echo $delimg;?></a>
			<a href="advs_con_list.php?group_id=<?php echo $re[$i]['ID']; ?>&group=<?php echo $_GET['group'];?>"><?php echo $setimg;?></a>
		</td>
      </tr>
    </form>
    <?php 
	 }
	?>
  </table>
  </div>
</div>
</body>
</html>
