<?php
	define('IN_DISCUZ', TRUE);
	define('UC_CLIENT_VERSION', '1.5.0');
	define('UC_CLIENT_RELEASE', '20081212');
	define('API_DELETEUSER', 1);
	define('API_RENAMEUSER', 1);
	define('API_GETTAG', 1);
	define('API_SYNLOGIN', 1);
	define('API_SYNLOGOUT', 1);
	define('API_UPDATEPW', 1);
	define('API_UPDATEBADWORDS', 1);
	define('API_UPDATEHOSTS', 1);
	define('API_UPDATEAPPS', 1);
	define('API_UPDATECLIENT', 1);
	define('API_UPDATECREDIT', 1);
	define('API_GETCREDITSETTINGS', 1);
	define('API_GETCREDIT', 1);
	define('API_UPDATECREDITSETTINGS', 1);
	define('API_RETURN_SUCCEED', '1');
	define('API_RETURN_FAILED', '-1');
	define('API_RETURN_FORBIDDEN', '-2');
	//================================================
	define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -3));
	include_once DISCUZ_ROOT.'/includes/global.php'; 
	$GLOBALS['db']=$db;
	
	error_reporting(0);
	set_magic_quotes_runtime(0);
	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}
	/*
	$fp=fopen('test.php','w+');
	$str=print_r($get,true);
	fwrite($fp,$str,strlen($str));
	fclose($fp);
	*/
	$timestamp = time();
	if($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}
	$action = $get['action'];

	require_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';

	if(in_array($action, array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings')))
	{
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} 
//===========================================

class uc_note {

	var $db = '';
	var $tablepre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = DISCUZ_ROOT;
		$this->db = $GLOBALS['db'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		$uids = $get['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		//----------------------------------------------
		global $db;
		$db->query("delete from ".ALLUSER." where userid in ('$uids')");
		$db->query("delete from ".USER." where userid in ('$uids')");
		//----------------------------------------------
		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}

		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		$name = $get['id'];
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}

		$name = trim($name);
		if(empty($name) || !preg_match('/^([\x7f-\xff_-]|\w|\s)+$/', $name) || strlen($name) > 20) {
			return API_RETURN_FAILED;
		}

		require_once $this->appdir.'./include/misc.func.php';
		$return = array($name, $threadlist);
		return $this->_serialize($return, 1);
	}

	function synlogin($get, $post)
	{
		$uid = $get['uid'];
		$username = $get['username'];
		global $db,$config;
		$sql="select * from ".ALLUSER." where user='$username'";
		$db->query($sql);
		$re=$db->fetchRow();
		if($re["userid"])
		{
			header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
			$c1=bsetcookie('USERID', "$re[userid]\t$username",time()+60*60*24,"/",$config['baseurl']);
			$c=setcookie('USER', $username,time()+60*60*24,"/",$config['baseurl']);
			//$fp=fopen('test.php','w+');
			//$str=$c.$config['baseurl'].$username.print_r($re,true);
			//fwrite($fp,$str,strlen($str));
			//fclose($fp);
		}

	}

	function synlogout($get, $post) {
		global $config;
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		bsetcookie('USERID', NULL,time()+60*60*24,"/",$config['baseurl']);
		setcookie('USER', NULL,time()+60*60*24,"/",$config['baseurl']);
	}

	function updatepw($get, $post) {
		$username = $get['username'];
		$password = $get['password'];
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		if(is_writeable($this->appdir.'./config.inc.php')) {
			$configfile = trim(file_get_contents($this->appdir.'./config.inc.php'));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($this->appdir.'./config.inc.php', 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}
		
		global $_DCACHE;
		require_once $this->appdir.'./forumdata/cache/cache_settings.php';
		require_once $this->appdir.'./include/cache.func.php';
		foreach($post as $appid => $app) {
			$_DCACHE['settings']['ucapp'][$appid]['viewprourl'] = $app['url'].$app['viewprourl'];
		}
		updatesettings();
	
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];

		require_once $this->appdir.'./forumdata/cache/cache_settings.php';

		$this->db->query("UPDATE ".$this->tablepre."members SET extcredits$credit=extcredits$credit+'$amount' WHERE uid='$uid'");

		$discuz_user = $this->db->result_first("SELECT username FROM ".$this->tablepre."members WHERE uid='$uid'");

		$this->db->query("INSERT INTO ".$this->tablepre."creditslog (uid, fromto, sendcredits, receivecredits, send, receive, dateline, operation)
				VALUES ('$uid', '$discuz_user', '0', '$credit', '0', '$amount', '$timestamp', 'EXC')");
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}

		$uid = intval($get['uid']);
		$credit = intval($get['credit']);
		return $credit >= 1 && $credit <= 8 ? $this->db->result_first("SELECT extcredits$credit FROM ".$this->tablepre."members WHERE uid='$uid'") : 0;
	}

	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		require_once $this->appdir.'./forumdata/cache/cache_settings.php';
		$credits = array();
		foreach($_DCACHE['settings']['extcredits'] as $id => $extcredits) {
			$credits[$id] = array(strip_tags($extcredits['title']), $extcredits['unit']);
		}
		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}
		$credit = $get['credit'];
		$outextcredits = array();
		if($credit) {
			foreach($credit as $appid => $credititems) {
				if($appid == UC_APPID) {
					foreach($credititems as $value) {
						$outextcredits[] = array(
							'appiddesc' => $value['appiddesc'],
							'creditdesc' => $value['creditdesc'],
							'creditsrc' => $value['creditsrc'],
							'title' => $value['title'],
							'unit' => $value['unit'],
							'ratiosrc' => $value['ratiosrc'],
							'ratiodesc' => $value['ratiodesc'],
							'ratio' => $value['ratio']
						);
					}
				}
			}
		}

		require_once $this->appdir.'./forumdata/cache/cache_settings.php';
		require_once $this->appdir.'./include/cache.func.php';

		$this->db->query("REPLACE INTO ".$this->tablepre."settings (variable, value) VALUES ('outextcredits', '".addslashes(serialize($outextcredits))."');", 'UNBUFFERED');

		$tmp = array();
		foreach($outextcredits as $value) {
			$key = $value['appiddesc'].'|'.$value['creditdesc'];
			if(!isset($tmp[$key])) {
				$tmp[$key] = array('title' => $value['title'], 'unit' => $value['unit']);
			}
			$tmp[$key]['ratiosrc'][$value['creditsrc']] = $value['ratiosrc'];
			$tmp[$key]['ratiodesc'][$value['creditsrc']] = $value['ratiodesc'];
			$tmp[$key]['creditsrc'][$value['creditsrc']] = $value['ratio'];
		}
		$_DCACHE['settings']['outextcredits'] = $tmp;

		updatesettings();

		return API_RETURN_SUCCEED;

	}
}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
?>