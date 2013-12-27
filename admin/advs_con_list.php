<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//===============================================
if(isset($_GET['deid']))
{
	$db->query("delete from ".ADVSCON." where ID='$_GET[deid]'");
}
$db->query("select * from ".ADVS." where id='$_GET[group_id]'");
$group_detail=$db->fetchRow();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8"> 
<link href="main.css" rel="stylesheet" type="text/css" />
</HEAD>
<body>
<div class="bigbox">
	<div class="bigboxhead">
		<?php echo lang_show('adv_menager').'-'.$group_detail['name'];?>
	</div>
	<div class="bigboxbody">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr class="theader"> 
      <td width="15%" align="left">名称 </td>
      <td width="9%" align="left"><?php echo lang_show('adv_type');?></td>
      <td width="9%" align="left">用户</td>
      <td width="10%" align="left"><?php echo lang_show('area');?></td>
      <td width="12%" align="left">投入类别</td>
      <td width="20%" align="left"><?php echo lang_show('start_or_end_time');?></td>
      <td width="9%" align="left">展示次数</td>
      <td width="8%"  align="center"><?php echo lang_show('statu');?></td>
      <td width="8%" align="center"><?php echo lang_show('operate');?></td>
    </tr>
    <?php
	$adt[1]=lang_show('text');
	$adt[2]=lang_show('code');
	$adt[3]=lang_show('image');
	$adt[4]=lang_show('flash');
	$tsql=NULL;
	if(!empty($_SESSION['province']))
		$tsql=" and a.province='$_SESSION[province]'";
	if(!empty($_SESSION['city']))
		$tsql=" and a.city='$_SESSION[city]'";
	if(!empty($_SESSION['area']))
		$tsql=" and a.city='$_SESSION[area]'";
	if(!empty($_GET['group_id']))
		$tsql.=" and a.group_id='$_GET[group_id]'";
	if(!empty($_GET['endtime']))
	{
		$time=time()-3600*24*7;
		$tsql.=" and a.etime<=$time";
	}
	$db->query("select a.*,u.user from ".ADVSCON." a left join ".USER." u on u.userid=a.userid where (a.userid is null or a.userid<1 or (a.userid>0 and a.statu=1)) $tsql order by ID desc");
	$re=$db->getRows();
	for($i=0;$i<count($re);$i++)
	{ 
	?>
    <form name="form1" method="post" action="">
      <tr> 
        <td align="left">
		<?php echo $re[$i]['isopen']?"<img src='../image/default/on.gif' />":"<img src='../image/default/off.gif' />";?>
		<?php echo $re[$i]['name'];?>&nbsp;</td>
        <td align="left"><?php $t=$re[$i]['type'];echo $adt[$t];?></td>
        <td align="left"><?php echo $re[$i]['user']==''?'管理员':"<a href='$config[weburl]/shop.php?uid=".$re[$i]['userid']."' target='_break' >".$re[$i]['user']."</a>";?></td>
        <td align="left"><?php if($re[$i]['province']||$re[$i]['city']||$re[$i]['area']) echo $re[$i]['province'].$re[$i]['city'].$re[$i]['area'];else echo lang_show('ishome'); ?></td>
        <td align="left"><?php echo $re[$i]['catid'];?> &nbsp;&nbsp;</td>
        <td align="left"><?php echo $re[$i]['stime']>0?date('Y-m-d',$re[$i]['stime']):''; ?>/<?php echo $re[$i]['etime']>0?date('Y-m-d',$re[$i]['etime']):''; 
		if($re[$i]['etime']<time()&&$re[$i]['etime']!='1970-01-01') echo '(已过期)';
		?></td>
		<td align="left"><?php echo $re[$i]['shownum'];?>&nbsp;</td>
        <td align="left"><?php echo $re[$i]['isopen']==1?'已启用':'未启用';?>&nbsp;</td>
        <td align="center">
		<a href="edit_adv_con.php?id=<?php echo $re[$i]['ID'];?>&type=<?php echo $re[$i]['type']; ?>&group_id=<?php echo $re[$i]['group_id']; ?>&group=<?php echo $_GET['group'];?>"><?php echo $editimg;?></a>
		<a href="?deid=<?php echo $re[$i]['ID'];?>&group_id=<?php echo $_GET['group_id'];?>&group=<?php echo $_GET['group'];?>" onClick="return confirm('<?php echo lang_show('are_you_sure');?>');">
		<?php echo $delimg;?>
		</a>
		</td>
      </tr>
    </form>
    <?php 
	 }
	?>
	<?php if($_GET['group_id']){?>
	<tr>
		<td colspan="9">
			<b>
			<a href="edit_adv_con.php?type=1&group_id=<?php echo $_GET['group_id']; ?>&group=<?php echo $_GET['group'];?>">+<?php echo lang_show('addadvs');?></a>&nbsp;&nbsp;
			<a href="advs.php?group=<?php echo $_GET['group'];?>">&lt;返回到广告组</a>
			</b>
		</td>
	</tr>
	<?php } ?>
  </table>
  </div>
</div>
</body>
</html>
