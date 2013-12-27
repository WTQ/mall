//------------------------------
var yi; 
function show(id)
{
	for(i=1;i<9;i++)
	{
		var yid="a"+i;
		if(document.getElementById(yid).style.backgroundImage=="url(image/default/bg_menu_on.gif)")
		{	 
			yi=yid; 
		}
		document.getElementById(yid).style.backgroundImage="";
	}
	document.getElementById(id).style.backgroundImage="url(image/default/bg_menu_on.gif)";
}

function hid(id)
{
	document.getElementById(id).style.backgroundImage="";
	if(yi)
		document.getElementById(yi).style.backgroundImage="url(image/default/bg_menu_on.gif)";

}
function hiid()
{
	if(yi)
		document.getElementById(yi).style.backgroundImage="";
}
function select_form(i,url)
{
	document.getElementById('sear').action=url+'/index.php';
	document.getElementById('m').value=i;
	document.getElementById('s').value=i+'_list';
}
function tabChang(i,url)
{
	ta='tag'+i;
	for(j=0;j<5;j++)
	{
		document.getElementById('tag'+j).className='';
	}
	document.getElementById(ta).className='current';
	
	if(i==2)
	{
		document.getElementById('sear').action=url+'/index.php';
		document.getElementById('m').value='company';
		document.getElementById('s').value='company_list';
	}
	if(i==0)
	{
		document.getElementById('sear').action=url+'/index.php';
		document.getElementById('m').value='product';
		document.getElementById('s').value='list';
	}
	if(i==3)
	{
		document.getElementById('sear').action=url+'/index.php';
		document.getElementById('m').value='news';
		document.getElementById('s').value='news_list';
	}
	if(i==4)
	{
		document.getElementById('sear').action=url+'/index.php';
		document.getElementById('m').value='exhibition';
		document.getElementById('s').value='exhibition_list';
	}
}
function setHomepage(url)
{
	if (document.all)
	{
		document.body.style.behavior='url(#default#homepage)';
		document.body.setHomePage(url);
	}
    else if (window.sidebar)
    {
    	if(window.netscape)
		{
			 try
			 {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			 }
			 catch(e)
			 {
				alert("Your browser permissions is denied");
			 }
		}
		var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
		prefs.setCharPref('browser.startup.homepage',url);
 	}
}
	
function myAddPanel(url,title)
{
	var desc=title;
	if ((typeof window.sidebar == 'object') && (typeof window.sidebar.addPanel == 'function'))
	{
		window.sidebar.addPanel(title,url,desc);
	}
	else
	{
		window.external.AddFavorite(url,title);
	}
}

function setTab(name,cursel,n)
{
	for(i=1;i<=n;i++)
	{
		var menu=document.getElementById(name+i);
		if(menu)
		{
			var con=document.getElementById("con_"+name+"_"+i);
			menu.className=i==cursel?"hover":"";
			con.style.display=i==cursel?"block":"none";
		}
	}
}
function get_randfunc(obj)
{
	var sj = new Date();
	obj.src=obj.src+'?'+sj;
}
function view_big_img(o, i)
{
	if(i!=''&&i.indexOf('nopic.gif') == -1)
	{	
		var s = i.replace('small','big'); 
		var aTag = o;
		var leftpos = toppos = 0;
		do
		{
			aTag = aTag.offsetParent;
			leftpos	+= aTag.offsetLeft;
			toppos += aTag.offsetTop;
		} 
		while(aTag.offsetParent != null);
		
		var X = o.offsetLeft + leftpos + 150;
		var Y = o.offsetTop + toppos - 20;
		document.getElementById('big_img_div').style.left = X + 'px';
		document.getElementById('big_img_div').style.top = Y + 'px';
		document.getElementById('big_img_div').style.display = 'block';
		document.getElementById('big_img_div').innerHTML='<img width=300 src="'+s+'"/>'
	} else {
		document.getElementById('big_img_div').style.display = 'none';
	}
}

$(document).ready(
	function()
	{
	  $("#allcat_title").mouseover(function (){
		  $('#cat_box').show();
		  $('#allcat_title').attr('className','allcat_title_on');
	  }
	  );
	  
	  $("#allcat_title").mouseout(function (){
		$('#cat_box').hide();
		$('#allcat_title').attr('className','allcat_title');
	  });
	}
);