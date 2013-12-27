<?php 
/**
 * 安装程序
 * @copyright Copyright (C) 2011 中国万网互联网解决方案事业部
 * @author bruce lee  liyongqing2008@gmail.com
 * @access public
 * @package system
*/
//设置当前系统标识
define('IN_HICHINA', TRUE);
//获取动作参数
$action = $_GET['action'];

//错误代码配置
$a_error =array
(
    "101"  => array("msg" => "网络传输错误", "description" => "Get发送或接收数据失败"),
    "102"  => array("msg" => "参数不完整", "description" => "无参数或参数个数不对"),
    "103"  => array("msg" => "身份不合法", "description" => "无权限调用接口"),
    "104"  => array("msg" => "解压缩失败", "description" => "压缩程序和解压缩程序不匹配"),
    "105"  => array("msg" => "配置文件未找到", "description" => "配置文件未找到"),
    "106"  => array("msg" => "配置文件内容不合法", "description" => "配置文件内容不合法"),
    "107"  => array("msg" => "请求地址无效", "description" => "独立应用接口文件不存在或无法打开"),
    "108"  => array("msg" => "配置文件无法修改", "description" => "配置文件无法修改"),
    "111"  => array("msg" => "参数不正确", "description" => "参数长度超长或类型不匹配"),
    "112"  => array("msg" => "接口已失效", "description" => "接口已超时"),
    "113"  => array("msg" => "安装失败", "description" => "安装失败"),
    "114"  => array("msg" => "运行检测失败", "description" => "检测到应用无法正常执行"),
    "121"  => array("msg" => "安装应用失败", "description" => "安装应用失败"),
    "122"  => array("msg" => "安装结果检测失败", "description" => "应用安装成功但运行检测失败"),
    "131"  => array("msg" => "无法连接数据库或数据库服务器无响应", "description" => "无法连接数据库或数据库服务器无响应"),
    "132"  => array("msg" => "添加账户失败", "description" => "添加管理员账户失败"),
    "200"  => array("msg" => "ok", "description" => "ok")
);
//输出XML
function outputXml($code)
{
	global $a_error;
	header("content-type: text/xml");
	echo '<?xml version="1.0" encoding="utf-8"?>
	<rsp>
	<code>' . $code . '</code>
	<msg>' . $a_error[$code]['msg'] . '</msg>
	</rsp>';
	exit();
}
//安装应用
//http://localhost/b2b/install/install.php?action=setup&dbhost=localhost&port=3306&dbname=hichina001_db&dbuser=root&dbpassword=root&tableprefix=b2bbuilder_&guid=6F9619FF-8B86-D011-B42D-00C04FC964FF

if($action == "setup")
{
	//检查参数是否完整
	$dbhost = $_GET['dbhost'];
	$port = $_GET['port'];
	$dbname = $_GET['dbname'];
	$dbuser = $_GET['dbuser'];
	$dbpassword = $_GET['dbpassword'];
	$tableprefix = $_GET['tableprefix'];
	$guid = $_GET['guid'];
	if(!$port)
		$port = 3306;

	if ($dbhost && $port && $dbname && $dbuser && $dbpassword && $tableprefix && $guid)
	{
		file_put_contents("db.txt", $dbhost.'|'.$port .'|'.$dbname .'|'.$dbuser .'|'.$dbpassword .'|'.$tableprefix.'|'.$guid);
		$link = mysql_connect($dbhost . ":" . $port, $dbuser, $dbpassword);
		if($link)
		{
			mysql_query("CREATE DATABASE IF NOT EXISTS `".$dbname."`;", $link);
			mysql_query("SET NAMES 'utf8',character_set_client=binary,sql_mode='';",$link);
			$link2 = mysql_select_db($dbname, $link);
			if($link2)
			{
				//==========================================================更新进度
				file_put_contents('progress.txt', 10);
				//安装步骤1. 创建数据库结构
				$sqlfile = 'B2Bbuilder.sql';
				$query = '';
				$fp = fopen(dirname(__FILE__).'/' . $sqlfile,'r');
				while($mysql=GetNextSQL())
				{
					mysql_query($mysql);
				}
				fclose($fp);
				//---------------------------------
				$rurl=$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
				$aurl = explode("/", $rurl);
				$realurl='';
				for($i=0;$i<count($aurl)-2;$i++)
					   $realurl=$realurl.$aurl[$i]."/";
				$realurl="http://".$realurl;
				$realurl=substr($realurl,0,-1);
				
				
				$burl=explode(".",$realurl);
				$pb=array_shift($burl);
				$baseurl=str_replace($pb.'.','',$_POST["weburl"]);
				$baseurl=str_replace('http://','',$_POST["weburl"]);
				$baseurl=explode('/',$baseurl);
				$baseurl=$baseurl[0];
				if(substr($baseurl,0,3)=='loc'||substr($baseurl,0,3)=='127')
					mysql_query("update  ".$tableprefix."web_config  set `value`='' where `index`='baseurl'");
				else
					mysql_query("update  ".$tableprefix."web_config  set `value`='".$baseurl."' where `index`='baseurl'");
				mysql_query("update  ".$tableprefix."web_config  set `value`='$realurl' where `index`='weburl'");
				//写系统配置文件
				$rsid=mysql_query("select * from  ".$tableprefix."web_config");
				$arr=array(); $configs=array();
				while($row=mysql_fetch_array($rsid))
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
				$cfp=fopen(dirname(__FILE__).'/../config/web_config.php','w');
				fwrite($cfp,$write_config_con_str,strlen($write_config_con_str));//将内容写入文件．
				fclose($cfp);
				//======================================================更新进度
				file_put_contents('progress.txt', 30);
				
				/*
				//安装步骤2. 导入测试数据
				$sqlfile = 'data.txt';
				$query = '';
				$fp = fopen(dirname(__FILE__).'/' . $sqlfile,'r');
				while(!feof($fp))
				{
					$line = rtrim(fgets($fp, 1024));
					if(preg_match("#;$#", $line))
					{
						$query .= $line;
						$query = str_replace('{tableprefix}',$tableprefix,$query);
						$rs = mysql_query($query,$link);
						$query='';
					} else if(!preg_match("#^(\/\/|--)#", $line))
					{
						$query .= $line;
					}
				}
				fclose($fp);			
				*/
				//更新进度
				file_put_contents('progress.txt', 70);
				//=======================================================安装步骤3. 配置文件修改
				$contents='<?php
					$config[\'dbhost\'] = \''.$dbhost.'\';      //数据库所在IP地址
					$config[\'dbuser\'] = \''.$dbuser.'\';  //数据库用户
					$config[\'dbpass\'] = \''.$dbpassword.'\';   	 //数据库密码
					$config[\'dbname\'] = \''.$dbname.'\';     //数据库名
					$config[\'port\'] = \''.$port.'\';     //端口
					$config[\'table_pre\']=\''.$tableprefix.'\';  //数据库表前缀
					$config[\'authkey\']=\''.md5(time().rand(0,100000)).'\';  //数据库表前缀
					?>';
				$filename = dirname(__FILE__)."/../config/config.inc.php"; 
				$cfp = fopen($filename,'w'); 
				fwrite($cfp,$contents); 
				fclose($cfp);
				//更新进度
				file_put_contents('progress.txt', 100);
				outputXml('200');
			}
			else
			{
				//数据库连接失败
				outputXml('131');
			}
    		mysql_close($link);
		}
		else
		{
			//数据库服务器连接失败
			outputXml('131');
		}
	}
	else
	{
		//参数不正确
		outputXml('102');
	}
}

//安装结果监测
//http://localhost/b2b/install/install.php?action=check&guid=6F9619FF-8B86-D011-B42D-00C04FC964FF
if($action == "check")
{
	$guid = $_GET['guid'];
	if($guid)
	{
		$progress = file_get_contents('progress.txt');
		header("content-type: text/xml");
		echo '<?xml version="1.0" encoding="utf-8"?>
		<rsp>
		<code>200</code>
		<msg>' .$progress . '</msg>
		</rsp>';
	}
	else
	{
		//参数不正确
		outputXml('102');
	}
}


//http://localhost/b2b/install/install.php?action=addadmin&adminuser=admin&adminpassword=1234

//增加管理员
if($action == "addadmin")
{
	$guid = '';
	$adminuser = $_GET['adminuser'];
	$adminpassword = md5(trim($_GET['adminpassword']));
	
	if ($adminuser && $adminpassword)
	{
		$dbinfo = file_get_contents("db.txt");
		$a_dbinfo = explode("|", $dbinfo);
		$dbhost = $a_dbinfo[0];
		$port = $a_dbinfo[1];
		$dbname = $a_dbinfo[2];
		$dbuser = $a_dbinfo[3];
		$dbpassword = $a_dbinfo[4];
		$tableprefix = $a_dbinfo[5];
		$guid = $a_dbinfo[6];
		$link = mysql_connect($dbhost, $dbuser, $dbpassword);
		if($link)
		{
			$link2 = mysql_select_db($dbname, $link);
			if($link2)
			{
				// 创建后台管理员测试帐号
				mysql_query("insert into ".$tableprefix."admin (password,user,type) value ('$adminpassword','$adminuser','1')");
				$result = mysql_query($sql);
				outputXml('200');
			}
			else
			{
				//数据库连接失败
				outputXml('131');
			}
			mysql_close($link);
		}
		else
		{
			//数据库服务器连接失败
			outputXml('131');
		}
	}
	else
	{
		//参数不正确
		outputXml('102');
	}
}

//==============================================
function GetNextSQL()
{
    global $fp,$tableprefix;
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
	$sql=str_replace("b2bbuilder_",$tableprefix,$sql);
    return $sql;
}
?>