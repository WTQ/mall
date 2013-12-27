function getScrollTop()
{
    var scrollTop=0;
    if(document.documentElement&&document.documentElement.scrollTop)
        return document.documentElement.scrollTop;
    else if(document.body)
        return document.body.scrollTop;
}
function preview(obj)
{	
	thisURL = document.URL;
	if(thisURL.indexOf('admin/')>0)
		weburl='../';
	else
		weburl='';
	title='Preview'
	msg='';
	w=400;
	h=300;
	src=weburl+'upload.php?pv=true&obj='+obj;
	alertWin(title, msg, w, h,src);
}
function uploadfile(title,obj,pw,ph,m,wm)
{
	thisURL = document.URL;
	if(thisURL.indexOf('admin/')>0)
		$weburl='../';
	else
		$weburl='';
	msg='';
	w=270;
	h=158;
	if(!m) m='';
	src=$weburl+'upload.php?pw='+pw+'&ph='+ph+'&obj='+obj+'&watermark='+wm+'&m='+m;
	alertWin(title, msg, w, h,src);
}

function delet_pic(obj)
{
	document.getElementById(obj).value='';
}

var temp_con='';
function alertWin(title, msg, w, h,src)
{
	var s=document.getElementsByTagName("select"); 
	for(var j=0;j<s.length;j++){s[j].style.display="none";}
	
	var titleheight = "20px";
	var border_color = "#666699";
	var titlecolor = "#FFFFFF";
	var titlebgcolor = "#1d5798";
	var bgcolor = "#FFFFFF";
	
	var iWidth = document.documentElement.clientWidth;
	var tHeight = document.documentElement.clientHeight;
	var iHeight = Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);
	var bgObj = document.createElement("div");
	bgObj.style.cssText = "position:absolute;left:0px;top:0px;width:"+iWidth+"px;height:"+Math.max(document.body.clientHeight, iHeight)+"px;filter:Alpha(Opacity=10);opacity:0.1;background-color:#000000;z-index:1000;";
	bgObj.id='bgObj';
	document.body.appendChild(bgObj);
	
	var msgObj=document.createElement("div");
	msgObj.style.cssText = "position:absolute;top:"+((tHeight-h)/2+getScrollTop())+"px;left:"+(iWidth-w) /2+"px;width:"+w+"px;height:"+h+"px;border:1px solid "+border_color+";background-color:"+bgcolor+";padding:1px;z-index:1003;";
	msgObj.id='msgObj';
	document.body.appendChild(msgObj);

	var table = document.createElement("table");
	msgObj.appendChild(table);
	table.style.cssText = "margin:0px;border:0px;padding:0px;width:100%";
	table.cellSpacing = 0;
	
	var tr = table.insertRow(-1);
	var titleBar = tr.insertCell(-1);
	titleBar.style.cssText = "width:*;height:"+titleheight+"px;text-align:left;padding-left:3px;margin:0px;cursor:move;";
	titleBar.style.paddingLeft = "10px";
	titleBar.innerHTML = title;
	tr.className = "titleBar";
	
	var moveX = 0;
	var moveY = 0;
	var moveTop = 0;
	var moveLeft = 0;
	var moveable = false;
	var docMouseMoveEvent = document.onmousemove;
	var docMouseUpEvent = document.onmouseup;
	titleBar.onmousedown = function()
	{
		var evt = getEvent();
		moveable = true;
		moveX = evt.clientX;
		moveY = evt.clientY;
		moveTop = parseInt(msgObj.style.top);
		moveLeft = parseInt(msgObj.style.left);
		document.onmousemove = function()
		{
			if (moveable)
			{
				var evt = getEvent();
				var x = moveLeft + evt.clientX - moveX;
				var y = moveTop + evt.clientY - moveY;
				if ( x > 0 &&( x + w < iWidth) && y > 0 && (y + h < iHeight) )
				{
					msgObj.style.left = x + "px";
					msgObj.style.top = y + "px";
				}
			}
		};
		document.onmouseup = function ()
		{
			if (moveable)
			{
				document.onmousemove = docMouseMoveEvent;
				document.onmouseup = docMouseUpEvent;
				moveable = false;
				moveX = 0;
				moveY = 0;
				moveTop = 0;
				moveLeft = 0;
			}
		};
	}
	
	var closeBtn = tr.insertCell(-1);
	closeBtn.innerHTML="<span class='closeBtn'>&nbsp;</span>";
	
	closeBtn.onclick = function()
	{
		for(var j=0;j<s.length;j++){s[j].style.display="";}
		document.body.removeChild(bgObj);
		document.body.removeChild(msgObj);
	}
	
	var msgBox = table.insertRow(-1).insertCell(-1);
	msgBox.colSpan  = 2;
	if(src==''&&msg=='')
		src='main.php?m=album&s=admin_album&nohead=true';
	else if(typeof(src)!='undefined')
		msgBox.innerHTML='<iframe style="width:'+w+'px; height:'+(h-30)+'px;" src="'+src+'" frameborder="0" scrolling="no"></iframe>';
	else
	{
		if(typeof(msg.innerHTML)!='undefined')
		{
			if(temp_con!='')
				msgBox.innerHTML =temp_con;
			else
			{
				msgBox.innerHTML = msg.innerHTML;
				temp_con=msg.innerHTML;
				msg.innerHTML='';
			}
		}
		else
			msgBox.innerHTML=msg;
	}
	function getEvent()
	{
		return window.event || arguments.callee.caller.arguments[0];
	}

}

function close_win()
{
	var s=document.getElementsByTagName("select"); 
	for(var j=0;j<s.length;j++){s[j].style.display="";}
	if(document.getElementById('bgObj'))
	{
		document.body.removeChild(document.getElementById('bgObj'));
	}
	if(document.getElementById('msgObj'))
	{
		document.body.removeChild(document.getElementById('msgObj'));
	}
}