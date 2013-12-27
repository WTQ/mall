<?php
@set_time_limit(0);
//关键字
if(!empty($_POST['search_flag'])&&!empty($_POST['key']))
{	
	include_once("includes/global.php");
	$sql="select * from ".SWORD." where keyword like '$_POST[key]%' or char_index like '$_POST[key]%' order by nums desc limit 0,10";
	$db->query($sql);
	$re=$db->getRows();
	foreach($re as $v)
	{
		echo "<a onclick=\"select_word('$v[keyword]')\" href='#'>$v[keyword]</a>";
	}
	die;
}
//邮箱是否重复
if(isset($_POST["check_email"])&&$_POST["check_email"]=='1')
{	
	include_once("includes/global.php");
	$sql="select * from ".ALLUSER." where email='$_POST[email]'";
	$db->query($sql);
	$re=$db->fetchRow();
	if($re['email']!='')
		echo 2;//不可以注册，已被使用
	else
		echo 1;//正常，可以注册。
}
//验证问题是否正确
if(isset($_POST["wtyz"])&&$_POST["wtyz"]=='1')
{	
	session_start();
	include_once("includes/global.php");
	$sql="select * from ".REGVERFCODE." order by rand() limit 0,1";
	$db->query($sql);
	$re=$db->fetchRow();
	echo $re['question'];
	$_SESSION['YZWT']=$re['answer'];
}
//验证码是否正确
if(isset($_POST["ckyzwt"]))
{	
	session_start();
	if($_POST["ckyzwt"]==$_SESSION['YZWT'])
		echo "true";
	else
		echo "false";
}
//验证码是否正确
if(isset($_GET["yzm"]))
{
	session_start();
	if(strtolower($_GET["yzm"])!=strtolower($_SESSION["auth"]))
	{
		echo 1;
	}
}
//会员是否重复
if(isset($_GET['user']))
{
	include_once("includes/global.php");
	include_once("config/reg_config.php");
	$config = array_merge($config,$reg_config);
	$un=trim($_GET['user']);
	$sql="select * from ".RESERVE_USERNAME." where username='$un'"; 
	$db->query($sql);
	if($db->num_rows()>0)
	{
		echo 1;
	}
	else
	{
	    if(!empty($config['openbbs'])&&$config['openbbs']==2)
	    {
			$sql="select * from ".ALLUSER." where user='$un'";
		    $db->query($sql);
		    $bbnum=$db->num_rows();
			
			include_once('uc_client/client.php');
		    if(uc_user_checkname($un)==1&&!$bbnum)
			    echo 0;//成功
		    else
			    echo 1;//失败
	    }
	    else
	    {
		    $sql="select * from ".ALLUSER." where user='$un'";
		    $db->query($sql);
		    if($db->num_rows()>0)
			   echo 1;//失败
		    else
			   echo 0;//成功
	    }
	}
}
//地区联动
if(isset($_POST["d_id"]))
{	
	include_once("includes/global.php");
	$sql="select id,name from ".DISTRICT." where pid='$_POST[d_id]' order by sorting";
	$db->query($sql);
	$num=$db->num_rows();
	$str="";
	if($num!=0)
	{
		$str="{";
		$i=0;
		while($k=$db->fetchRow())
		{
			$i++;
			$id=$k["id"];
			$name=$k["name"];
			if($i<$num)
				$str.="'$i':{'0':'$id','1':'$name'},";
			else
				$str.="'$i':{'0':'$id','1':'$name'}";
		}
		//------------------------------------------------------------
		$str.="};";
	}
	echo $str;
}
//产品商铺分类联动
if(!empty($_POST["pcatid"]))
{
	include_once("includes/global.php");
	
	$s=$_POST["pcatid"]."00";$b=$_POST["pcatid"]."99";
	if(!empty($_POST['cattype'])&&$_POST['cattype']=='pro')
		$db->query("SELECT * FROM ".PCAT." WHERE catid>'$s' and catid<'$b' ORDER BY nums ASC");
	
	if(!empty($_POST['cattype'])&&$_POST['cattype']=='com')
		$db->query("SELECT * FROM ".SHOPCAT." WHERE parent_id='$_POST[pcatid]' ORDER BY displayorder");
	
	$num=$db->num_rows();
	$str="{";
	$i=0;
	while($k=$db->fetchRow())
	{
		$i++;
		if($_POST['cattype']=='com')
		{
			$city_id=$k["id"];
			$cat=str_replace("\r",'',$k['name']);
		}
		else
		{
			$city_id=$k["catid"];
			$cat=str_replace("\r",'',$k['cat']);
		}
		if($i<$num)
			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"},";
		else
			$str.="\"$i\":{\"0\":\"$city_id\",\"1\":\"$cat\"}";
	}
	$str.="};";
	echo $str;
}

?>