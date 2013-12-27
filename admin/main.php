<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
$config['language'] = isset($_SESSION["ADMIN_LANG"])?$_SESSION["ADMIN_LANG"]:$config['language'];
include_once("../lang/" . $config['language'] . "/admin.php");
include_once("auth.php");
include_once("menu_config.php");
//====================================
if(empty($_SESSION["ADMIN_USER"]))
	msg("index.php");

function list_sub($sv,$sub=NULL)
{
	global $perm,$lang,$bnums,$j;
	$u_v=explode(",",$sv);
	if($u_v[1]==1&&(in_array(md5($u_v[0]),$perm)||$_SESSION["ADMIN_TYPE"]=='1'))
	{
		$str.='<li>';
		if(!empty($u_v[2]))
			$str.='<a href="module.php?m='.$u_v[2].'&s='.$u_v[0].'"  hidefocus="true">';
		else
			$str.="<a href='$u_v[0]' hidefocus='true' >";
		
		$sar=explode('?',$u_v[0]);
		$u_v[0]=$sar[0];
		$scrp_name=substr($u_v[0],0,-4);
		if(!empty($u_v[3]))
			$str.=$u_v[3];
		else
			$str.=$lang[$scrp_name];
		$str.='<em> </em></a></li>';
	}
	return $str;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo lang_show('business_manager_system');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../script/jquery-1.4.4.min.js"></script>
</head>
<body style="overflow:hidden;">
<table width="100%" height="100%" id="frametable" cellpadding="0" cellspacing="0">
	<tr>
    	<td colspan="2" height="60">
        	<div id="header">
            	<div class="logo"><img width="180" src="../image/admin/logo.gif"></div>
            	<div class="info">
				<p class="portrait"><?php echo "您好: ".$_SESSION["ADMIN_USER"];?></p>
                <p><a href="index.php?action=logout" target="_top"><?php echo lang_show('log_out');?></a></p>
                <p><a target="_blank" href="<?php echo $config['weburl'];?>"><?php echo lang_show('siteindex');?></a></p>            
                </div>
            	<div class="nav">
                    <ul id="topmenu">
					<?php
                       if(empty($perm))
                            $perm=array();
                        
                        foreach($mem as $key=>$v)
                        {
                            if(in_array(md5($key),$perm)|| $_SESSION["ADMIN_TYPE"]=='1')
                            {
                                $arr=@explode(",",$v[1][0][1][0]);
								if($arr[2])
                                    $to_url="module.php?m=$arr[2]&s=$arr[0]";
                                else	
                                    $to_url=$arr[0];
                         ?>
                        
                        <li><a id="header_<?php echo $key;?>" onClick="toggleMenu('<?php echo $key;?>','<?php echo $to_url;?>'); return false"><?php echo $v[0];?></a> </li>
                        <?php
                             }
                        }
                    ?>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
    <tr>
    	<td width="180" valign="top" class="menutd">
        <div class="menu" id="leftmenu">
		<?php
		//print_r($mem);
		foreach($mem as $key=>$v)
		{
			$con=NULL;
			if($v[1])
			{	
				foreach($v[1] as $skey=>$sv)
				{	
					$left_con=NULL;
					foreach($sv[1] as $sub_sv)
					{
						$left_con.=list_sub($sub_sv);
					}
					if(!empty($sv[0]))
					{
						$con.="<li class='s'><div class='minus'><div onclick='opendiv(this.parentNode)'>$sv[0]</div><ol>$left_con</ol></div></li>";
					}
					else
					{
						$con.=$left_con;
					}
				}
			}
			echo "<ul id='menu_$key' style='display:none'>$con</ul>";
		}
        ?>
        </div>
        </td>
        <td height="100%" valign="top" class="frame"><iframe id="main" name="main" width="100%" frameborder="0" height="100%"></iframe></td>
    </tr>
</table>
<div style="" id="scrolllink">
	<span onClick="menuScroll(1)"><img src="../image/admin/scrollu.gif"></span>
    <span onClick="menuScroll(2)"><img src="../image/admin/scrolld.gif"></span>
</div>
<script>
var headers = new Array('index','global','pay','product','member','shop','business','website','running','tools');
var menukey = '';
function toggleMenu(key, url) {
	switchheader(key);
	menukey = key;
	if(url) {
		parent.main.location = url;
		var hrefs = document.getElementById('menu_' + key).getElementsByTagName('a');
		for(var j = 0; j < hrefs.length; j++) {
			hrefs[j].className = j == 0 ? 'tabon' : '';
		}
	}
	
	setMenuScroll();
}
function setMenuScroll() {
	var obj = document.getElementById('menu_' + menukey);
	if(!obj) {
		return;
	}
	var scrollh = document.body.offsetHeight - 160;
	obj.style.overflow = 'visible';
	obj.style.height = '';
	document.getElementById('scrolllink').style.display = 'none';
	
	if(obj.offsetHeight + 150 > document.body.offsetHeight && scrollh > 0) {
		obj.style.overflow = 'hidden';
		obj.style.height = scrollh+10 + 'px';
		document.getElementById('scrolllink').style.display = '';
	}
}
function menuScroll(op, e) {
	var obj = document.getElementById('menu_' + menukey);
	var scrollh = document.body.offsetHeight - 160;
	if(op == 1) {
		obj.scrollTop = obj.scrollTop - scrollh;
	} else if(op == 2) {
		obj.scrollTop = obj.scrollTop + scrollh;
	} else if(op == 3) {
		if(!e) e = window.event;
		if(e.wheelDelta <= 0 || e.detail > 0) {
			obj.scrollTop = obj.scrollTop + 20;
		} else {
			obj.scrollTop = obj.scrollTop - 20;
		}
	}
}
function switchheader(key) {
	for(var k in headers) {
		if(document.getElementById('menu_' + headers[k])) {
			document.getElementById('menu_' + headers[k]).style.display = headers[k] == key ? '' : 'none';
		}
	}
	var lis = document.getElementById('topmenu').getElementsByTagName('li');
	for(var i = 0; i < lis.length; i++) {
		if(lis[i].className == 'navon') lis[i].className = '';
	}
	document.getElementById('header_' + key).parentNode.className = 'navon';
}

var menus = document.getElementById('leftmenu').getElementsByTagName('a');
for(var i = 0; i < menus.length; i++) {
	var menu = menus[i];
	menu.onclick = function() {
		for(var i = 0; i < menus.length; i++)
		{
			menus[i].className = '';
		}
		parent.main.location = this.href;
		this.className = 'tabon';
		return false;
	}
}
function opendiv(obj) {
	
	obj.className = obj.className != 'add' ? 'add' : 'minus';
	setMenuScroll();
}
function _attachEvent(obj, evt, func) {
	if(obj.addEventListener) {
		obj.addEventListener(evt, func, false);
	} else if(obj.attachEvent) {
		obj.attachEvent("on" + evt, func);
	}
}
toggleMenu('index','main_index.php');

_attachEvent(window, 'resize', setMenuScroll, document);

function closeWin(url)
{
	close_win();
	document.getElementById('main').src=url;
}
</script>
</body>
</html>
