<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//===========================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>
<script type="text/javascript">
function show(n)
{
	var d=document.getElementById ("div"+n);
	var a = document.getElementsByTagName("a");
	if(d)
	{
		d.style.display = "block";
		d.style.left = document.documentElement.scrollLeft + a[n].clientX + "px";
		d.style.top  = document.documentElement.scrollTop  + a[n].clientY + "px";
	}
}
function hid(n)
{
	var d=document.getElementById ("div"+n);
	if(d)
	{
		d.style.display = "none";
	}
}
</script>
<body>
<?php
include_once("../includes/arrcache_class.php");
$caches     = new ArrCache('../cache/front');
$cachetime = 900;//数据调用缓存时间。
if(!$caches->begin('pageview',$cachetime))
{
		
	if($config['openstatistics'])
	{
		$sql="select ip,username,count(*) as num from ".PV." group by ip order by num desc, ip asc,username desc limit 10";
		$db->query($sql);
		$ipNum=$db->num_rows();
		$list=$db->getRows();
		
		$sql="select url,count(*) as urlnum from ".PV." group by url order by urlnum desc, url asc limit 10";
		$db->query($sql);
		$urlnum=$db->num_rows();
		$urllist=$db->getRows();
		
		$sql="select count(*) as num from ".PV;
		$db->query($sql);
		$rs=$db->fetchRow();
		$pvs=$rs['num'];//pv总数
		//------------------------------
		$sql="select count(distinct ip) as ips from ".PV;
		$db->query($sql);
		$rs=$db->fetchRow();
		$ips=$rs['ips'];//独立ip总数
		//-----------------------------------
		$sql="select count(url) as urls from ".PV;
		$db->query($sql);
		$rs=$db->fetchRow();
		$urls=$rs['urls'];//url总数
		//-------------------------------------
		$sql="select  url,count(*) as num from ".PV." group by url order by num desc limit 1";
		$db->query($sql);
		$rs=$db->fetchRow();
		$mostpopurl=$rs['url'];//最受欢迎的url
		$urlvisitnum=$rs['num'];//访问次数
		//-----------------------------------
		$sql="select count(distinct username) as users from ".PV." where username<>''";
		$db->query($sql);
		$rs=$db->fetchRow();
		$onusers=$rs['users'];//上线会员数
		//-----------------------------------
		$sql="select count(*) as reguser from ".ALLUSER." where TO_DAYS(NOW())-TO_DAYS(regtime)=0";
		$db->query($sql);
		$rs=$db->fetchRow();
		$nregusers=$rs['reguser'];//新注册会员数
		//-------------------------------------
		$sql="select count(*) as offers from ".PRO." where TO_DAYS(NOW())-TO_DAYS(uptime)=0";
		$db->query($sql);
		$rs=$db->fetchRow();
		$offers=$rs['offers'];//新发布产品数
		//-----------------------------------------
		$sql="select count(*) as newss from ".NEWSD." where TO_DAYS(NOW())-TO_DAYS(uptime)<=1";
		$db->query($sql);
		$rs=$db->fetchRow();
		$newss=$rs['newss'];//新发布资讯数
		//--------------------------目前游客数
		$nowonline=time()-600;
		$nt=date("Y-m-d H:i:s",$nowonline);
		$sql="select count(distinct ip) as nouss from ".PV." where username='' and time>'$nt' order by time desc";
		$db->query($sql);
		$rs=$db->fetchRow();
		$nousers=$rs['nouss'];//游客数
		//--------------------------目前在线会员
		$nowonline=time()-600;
		$nt=date("Y-m-d H:i:s",$nowonline);
		$sql="select   * from ".PV." where username<>'' and time>='$nt' group by username order by time desc";
		$db->query($sql);
		$rs=$db->getRows();
	}
?>
<link href="main.css" rel="stylesheet" type="text/css" />
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('ttitle');?></div>
  <div class="bigboxbody">
    <?php
	 if(!$config['openstatistics'])
	 {
		admin_msg('system_config.php',"统计功能已关闭，请在系统设置中将统计功能打开");
	 }
	 else
	 {
	 ?>
    <table width="100%" border="0" cellpadding="1" cellspacing="0">
      <tr>
        <td width="138"  align="left"  ><strong><?php echo lang_show('ips');?></strong></td>
        <td width="304" align="left"  ><?php echo $ips;?></td>
        <td width="170" align="left" scope="row" ><strong><?php echo lang_show('offer');?></strong></td>
        <td width="386" align="left" ><?php echo $offers;?></td>
      </tr>
      <tr>
        <td  align="left" scope="row" ><strong><?php echo lang_show('pvs');?></strong></td>
        <td align="left" ><?php echo $pvs;?></td>
        <td  align="left" scope="row" ><strong><?php echo lang_show('newss');?></strong></td>
        <td align="left" ><?php echo $newss;?></td>
      </tr>
      <tr>
        <td  align="left" scope="row" ><strong><?php echo lang_show('urls');?></strong></td>
        <td align="left" ><?php echo $urls;?></td>
        <td  align="left" scope="row" ><strong><?php echo lang_show('todayreguser');?></strong></td>
        <td align="left" ><?php echo $nregusers;?></td>
      </tr>
      <tr>
        <td  align="left" scope="row" ><strong><?php echo lang_show('todayonlineuser');?></strong></td>
        <td align="left" ><?php echo $onusers;?></td>
        <td align="left" scope="row" ><strong><?php echo lang_show('onlinevs');?></strong></td>
        <td align="left" ><?php echo $nousers;?></td>
      </tr>
      <tr>
        <td  align="left" scope="row" ><strong><?php echo lang_show('murls');?></strong></td>
        <td align="left" ><?php echo urldecode($mostpopurl);?>:(<?php echo $urlvisitnum;?>)</td>
        <td  align="left" scope="row" >&nbsp;</td>
        <td align="left" >&nbsp;</td>
      </tr>
      <tr>
        <td  align="left" scope="row" ><strong><?php echo lang_show('nowonlineu');?></strong></td>
        <td colspan="3" align="left" ><?php 
		   foreach($rs as $key=>$u)
		   {
			  echo "<a href='#' title='$u[url]' >".$u['username']."</a>&nbsp;&nbsp;";
			  if(($key+1)%10==0)
				echo '<br>';
		   }
		   ?></td>
      </tr>
    </table>
  </div>
</div>
<div style="float:left; height:20px; width:80%;">&nbsp;</div>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('ipnot');?></div>
  <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr class="theader">
        <td width="291" ><?php echo lang_show('ip');?></td>
        <td width="339"><?php echo lang_show('user');?></td>
        <td width="293"><?php echo lang_show('view_count');?></td>
        <td width="100"><?php echo lang_show('option');?></td>
      </tr>
      <?php
	if(is_array($list))
	{
	foreach($list as $value)
	{
	?>
      <tr>
        <td ><?php echo $value['ip']; echo '['.convertip($value['ip'], '../lib/tinyipdata.dat').']';?></td>
        <td ><?php echo $value['username']; ?>&nbsp;</td>
        <td ><?php echo $value['num']; ?></td>
        <td ><a href="iplockset.php?ip=<?php echo $value['ip'];?>"><?php echo lang_show('forbidden');?></a></td>
      </tr>
      <?php
	}}
	?>
    </table>
  </div>
</div>
<div style="float:left; height:20px; width:80%;">&nbsp;</div>
<div class="bigbox">
  <div class="bigboxhead"><?php echo lang_show('urlnot');?></div>
  <div class="bigboxbody">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr class="theader">
        <td width="565" >URL</td>
        <td width="132"><?php echo lang_show('view_count');?></td>
      </tr>
      <?php
		if(is_array($urllist))
		{
			foreach($urllist as $value)
			{
				?>
      <tr>
        <td><?php echo urldecode($value['url']); ?></td>
        <td><?php echo $value['urlnum']; ?></td>
      </tr>
      <?php
			 }
		 }
	 }
	?>
    </table>
  </div>
</div>
<?php 
}
$caches->end();
?>
</body>
</html>
