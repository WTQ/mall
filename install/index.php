<?php
header("Content-type: text/html;charset=utf-8");
error_reporting(0);
@set_time_limit(0);
set_magic_quotes_runtime(0);
if(file_exists("../install/isinstall.lock"))
{
	echo "<script language=\"JavaScript\">alert(\"系统已经安装!如需要重新安装，请手动删除install/isinstall.lock文件后再重新安装\");</script>";
	exit(); 
}
isset($_REQUEST['GLOBALS']) && exit('Access Error');
@$action=$_GET["action"];
switch ($action)
{
	case 'b2b_step2':
	{
		require('templates/b2b_step2.html');
		break;
	}
	case 'b2b_step3':
	{
		if(function_exists( 'mysql_connect')){
			 $mysql_support  = 'ON';
			 $mysql_ver_class ='OK';
			 $checkmysql=1;
		}else {
			 $mysql_support  = 'OFF';
			 $mysql_ver_class ='WARN';
			  $checkmysql=0;
		}
		global $errormsg;
		global $ver_class;
		if(PHP_VERSION<'4.1.0')
		{
			$ver_class = 'WARN';
			$errormsg['version']='php 版本过低,必须4.1及以上';
			$checkv=0;
		}
		else 
		{
			$ver_class = 'OK';
			$checkv=1;
		}
		$dir='../cache';
		$handle = opendir($dir);
		$i=0;
        global $w_check;
        $w_check=array();
		while ($filename = readdir($handle))
	    { 
			if($filename!="."&&$filename!="..")
			{
				$sdir = $dir.'/'.$filename;
				if(is_dir($sdir))
				{
					$w_check[$i]=$sdir;
					$i=$i+1;
				}
			}
	    }
        $dir='../templates_c';
		$handle = opendir($dir);
		while ($filename = readdir($handle))
	    { 
			if($filename!="."&&$filename!="..")
			{
				$sdir = $dir.'/'.$filename;
				if(is_dir($sdir))
				{
					$w_check[$i]=$sdir;
					$i=$i+1;
				}
			}	
	    }
		$dir='../uploadfile';
		$handle = opendir($dir);
		while ($filename = readdir($handle))
	    { 
			if($filename!="."&&$filename!="..")
			{
				$sdir = $dir.'/'.$filename;
				if(is_dir($sdir))
				{
					$w_check[$i]=$sdir;
					$i=$i+1;
				}
			}	
	    }
        $w_m=array('../config');
        $databackup=array('../databackup');
		$w_check=array_merge($w_check,$w_m);
		$w_check=array_merge($w_check,$databackup);
		$class_chcek=array();
		$check_msg = array();
		$count=count($w_check);
		$checkup=1;
		for($i=0; $i<$count; $i++)
		{
            if(is_writable($w_check[$i]))
			{
				$check_msg[$i]= '通 过';
				$class_chcek[$i] = 'OK';
				$checkup=1 and $checkup;
			} 
			else
			{
				$check_msg[$i]='777属性检测不通过';
				$checkup=0;
				$class_chcek[$i] = 'WARN';
				$checkup=0 and $checkup;
			}
		}
		if(extension_loaded('gd')) 
			$checkgd=1;
		else
			$checkgd=0;
		global $disabled;
		if($checkmysql==0||$checkv==0||$checkup==0||$checkgd==0)
			$disabled = 'disabled';
		else
            $disabled='';
		require('templates/b2b_step3.html');
		break;
	}
	case 'b2b_step4':
	{
		require('templates/b2b_step4.html');
		break;
	}
	case 'b2b_step5':
	{    
		$dbhost=trim($_POST['db_host']);
		$dbport=trim($_POST['db_port']);
		$dbuser=trim($_POST['db_username']);
		$dbpass=trim($_POST['db_pass']);
		$dbname=trim($_POST['db_name']);
		$dbtable=trim($_POST['db_table']);
		@mysql_connect("$dbhost:$dbport", $dbuser, $dbpass) or die("不能连接数据库 $dbhost"."请输入正确的数据库地址、用户名和密码！<br><a href='index.php?action=b2b_step4'>返回上一步重新输入mysql地址、用户名和密码</a>");
		if(@version_compare(mysql_get_server_info(), '4.1.0', '>='))
		{
			@mysql_query("set names utf8");
			$file_name="MallBuilder.sql"; //要导入的SQL文件名
		}
		else 
		{
			$file_name="B2Bbuilder4.sql"; //要导入的SQL文件名
			echo "您的mysql版本过低，可能会影响你的安装,但官方建议您升级到mysql4.1.0以上";
		}
	   //系统数据库开始导入
	   if(@mysql_select_db($dbname))
	   {
			$csql="select * from  ".$dbtable."admin where 1=0";
			if (mysql_query($csql))
			{
				if (!empty($_POST['renewinstall']))
				{
					  @mysql_select_db($dbname);
					  $result =@mysql_list_tables($dbname);
					  $trows=@mysql_num_rows($result);
					  for ($i = 0; $i <$trows; $i++)
					  {
						  $oldtablename=explode("_", mysql_tablename($result, $i));
						  if ($oldtablename[0]."_"==trim($dbtable))
							  mysql_query("drop table if exists `".mysql_tablename($result, $i)."`");
					  }
				}
				else
				{
					 echo "<script language=\"JavaScript\">alert(\"你输入的数据库里面已经存在本系统的表，你可以换一个数据库名称或者输入新的数据表前缀，并且选择覆盖安装模式！\");</script>";
					 require('templates/b2b_step4.html');
					 exit(); 
				}
			}
	  }
	  else
	  {
			$sql="CREATE DATABASE $dbname";
			$myd=mysql_query($sql);
			if (empty($myd))
			{
			   echo "创建数据库失败！请确认你是有创建数据库权限！";
			   exit();
			}
	  }
	   @mysql_select_db($dbname) or die ("打开数据库 $dbname 出现未知错误！无法正常连接数据库！请重新安装，如果不能解决问题，请与技术求助！");//打开数据库
	   $fp = @fopen($file_name, "r") or die("不能打开文件 $file_name 请检查文件是否存在，并且检查该文件夹的权限!");//打开文件
	   while($mysql=GetNextSQL())
	   {
			if (!mysql_query($mysql))
			{
				if(mysql_error()!='Query was empty')
				{
				 echo "执行出错：".mysql_error()."<br>";
				 echo "SQL语句为:".$mysql."<br>";
				}
			}
	   }
	   fclose($fp) or die("Can't close file $file_name");//关闭文件
	   $newpassword=md5($_POST["adminpass"]);
	   @mysql_select_db($dbname) or die ("打开数据库 $dbname 出现未知错误！无法正常连接数据库！请重新安装，如果不能解决问题，请与技术求助！");//打开数据库 
	   mysql_query("update  ".$dbtable."admin set  password='$newpassword' where user='admin'");
	   mysql_query("update  ".$dbtable."web_config  set `value`='".$_POST["weburl"]."' where `index`='weburl'");
	   $burl=explode(".",$_POST["weburl"]);
	   $pb=array_shift($burl);
	   $baseurl=str_replace($pb.'.','',$_POST["weburl"]);
	   $baseurl=str_replace('http://','',$_POST["weburl"]);
	   $baseurl=explode('/',$baseurl);
	   $baseurl=$baseurl[0];
	   if(substr($baseurl,0,3)=='loc'||substr($baseurl,0,3)=='127')
			mysql_query("update  ".$dbtable."web_config  set `value`='' where `index`='baseurl'");
	   else
			mysql_query("update  ".$dbtable."web_config  set `value`='".$baseurl."' where `index`='baseurl'");
	   //写系统配置文件
	   $rsid=mysql_query("select * from  ".$dbtable."web_config where type='main'");
	   if (empty($rsid))
		   echo "写系统配置文件出错!请使用系统默认的配置文件，然后进入后台修改系统配置！";
	   $arr=array(); 
	   $configs=array();
	   while ($row=mysql_fetch_array($rsid))
	   { 
		  	$arr[] = $row; 
	   } 
	   foreach($arr as $v)
	   {
			$index=$v['index'];
			$value=$v['value'];
			$configs[$index]=$value;
	   }
	   $write_config_con_array=$configs;
	   $write_config_con_str=serialize($write_config_con_array);//将数组序列化后生成字符串
	   $write_config_con_str='<?php $config = array_merge($config, unserialize(\''.$write_config_con_str.'\'));?>';//生成要写的内容
	   $cfp=fopen('../config/web_config.php','w');
	   fwrite($cfp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
	   fclose($cfp);
	   //写系统配置文件结束
	   mysql_close();
	   //系统数据库导入结束
	   //配置config.ini.php文件设置开始
		$contents='<?php
		$config[\'dbhost\'] = \''.$dbhost.'\';      //数据库所在IP地址
		$config[\'dbport\'] = \''.$dbport.'\';  //数据库用户
		$config[\'dbuser\'] = \''.$dbuser.'\';  //数据库用户
		$config[\'dbpass\'] = \''.$dbpass.'\';   	 //数据库密码
		$config[\'dbname\'] = \''.$dbname.'\';     //数据库名
		$config[\'table_pre\']=\''.$dbtable.'\';  //数据库表前缀
		$config[\'authkey\']=\''.md5(time().rand(0,100000)).'\';  //数据库表前缀
		?>';
	   $filename = "../config/config.inc.php"; 
	   $cfp = fopen($filename,'w'); 
	   fwrite($cfp,$contents); 
	   fclose($cfp);
	   $cfp = fopen("../install/isinstall.lock",'w');
	   fwrite($cfp,"B2Bbuilder is installed !");
	   fclose($cfp); 
	   //配置文件结束
	   //销毁自定义的全局变量
	   unset($GLOBALS['disabled']);
	   unset($GLOBALS['mysql_support']);
	   unset($GLOBALS['mysql_ver_class']);
	   unset($GLOBALS['errormsg']);
	   unset($GLOBALS['ver_class']);
	   unset($GLOBALS['errormsg']);
	  //销毁自定义全局变量结束
	   require('templates/b2b_step5.html');
	   break;
	}
	default:
	{
		require("templates/b2b_step1.html");
	}
}
//从sql文件中逐条取SQL
function GetNextSQL()
{
    global $fp;
    $sql="";
    while ($line = @fgets($fp, 40960))
    {
		$line = trim($line);
		//以下三句在高版本php中不需要
		$line = str_replace("\\\\","\\",$line);
		$line = str_replace("\'","'",$line);
		$line = str_replace("\\r\\n",chr(13).chr(10),$line);
		$line = stripcslashes($line);
		if (strlen($line)>1)
		{
			if ($line[0]=="-" && $line[1]=="-")
			{
				continue;
			}
		}
		$sql.=$line.chr(13).chr(10);
		if (strlen($line)>0)
		{
			if ($line[strlen($line)-1]==";")
			{
			break;
			}
		}
    }
	global $dbtable;
	$sql=str_replace("hy_",$dbtable,$sql);
	$sql=str_replace("b2bbuilder_",$dbtable,$sql);
	$sql=str_replace("mallbuilder_",$dbtable,$sql);
    return $sql;
}
?>