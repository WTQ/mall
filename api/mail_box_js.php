<?php
include_once("../includes/global.php");
include_once("../config/config.inc.php");
?>
document.write('<div id="mailpop" style="position:absolute; right:0px;"><iframe src="api/mail_box.php?uid=<?php echo $_GET["uid"]; ?>" name="scbox" id="scbox" scrolling="no" frameborder="0" width="202" height="<?php if(isset($_COOKIE["USER"])) echo 208;else echo 257;?>"></iframe></div>');
win_obj=document.getElementById('mailpop');
win_obj.style.top="400px";
win_obj.style.zIndex="3";
wh=<?php if(isset($_COOKIE["USER"])) echo 208;else echo 257;?>*1;
function tomin()
{
	document.getElementById("scbox").height=22;
    sc5();
}
function tomax()
{
	document.getElementById("scbox").height=wh;
    sc5();
}
function hidMailPop()
{
	document.getElementById("mailpop").style.display="none"; 
}

function getscrollTop()//浏览器兼容document.documentElement.scrollTop
{
	var scrollPos; 
	if (typeof window.pageYOffset != 'undefined') { 
	   scrollPos = window.pageYOffset; 
	} 
	else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') { 
	   scrollPos =document.documentElement.scrollTop; 
	} 
	else if (typeof document.body != 'undefined') { 
	   scrollPos =document.body.scrollTop; 
	} 
	return scrollPos;
}

function sc5()
{
win_obj.style.top=(getscrollTop()+document.documentElement.clientHeight-win_obj.offsetHeight)+"px";
win_obj.style.left=(document.documentElement.scrollLeft+document.documentElement.clientWidth-win_obj.offsetWidth)+"px";
}
window.onscroll=sc5;
window.onresize=sc5;
window.onload=sc5;