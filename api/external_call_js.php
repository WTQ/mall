<?php
include_once("../includes/global.php");
include_once("../config/config.inc.php");
if(!empty($_GET['limit'])&&is_numeric($_GET['limit']))
   $limit=$_GET['limit'];
else
   $limit=10;
if(!empty($_GET['class'])&&$_GET['class']=='pro')
{
	   if($_GET['type']=='new')
		   $sql="SELECT * from ".PRO." where statu>0 order by rank desc,uptime desc limit ".$limit;
	   if($_GET['type']=='rec')
			$sql="SELECT * from ".PRO." where statu=2 order by rank desc,uptime desc limit ".$limit;
	   if(empty($sql))die();
	   $db->query($sql);
	   $prol=$db->getRows();
	   $str='';
	   foreach($prol as $v)
	   {
		   if(!empty($_GET['img']))
				$img="<img src='".$config['weburl']."/uploadfile/comimg/small/".$v['id'].".jpg'/><br>";
		   else
			   $img='';
		   $str.="<li>".$img."<a href='".$config['weburl']."/shop.php?action=prod&uid=".$v['userid']."&id=".$v['id']."' target='_blank'>".$v['pname']."</a></li>";
	   }
}
?>
document.write("<?php echo $str;?>");