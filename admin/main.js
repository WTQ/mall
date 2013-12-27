function showCat(id)
{
	document.getElementById("catid"+id).style.display="block";
}
function getHTML(v,ob,id,cattype)
{	

	var url = '../ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&pcatid='+v;
	if(cattype=='offer')
		pars += '&cattype=offer';
	if(cattype=='pro')
		pars += '&cattype=pro';
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		var tempStr = 'var MyMe = ' + originalRequest; 
		var i=1;var j=0;
		eval(tempStr);
		for(var s in MyMe)
		{
			++j;
		}
		if(j>0)
			document.getElementById(ob).style.display="block";
		else
			document.getElementById(ob).style.display="none";
		document.getElementById(ob).options.length =j+1;
		for(var k in MyMe)
		{
			var cityId=MyMe[k][0];
			var ciytName=MyMe[k][1];
			document.getElementById(ob).options[k].value = cityId;
			document.getElementById(ob).options[k].text = ciytName;
	　	}
		document.getElementById("mod"+id).style.display="block";
	 }
　}
function updateCat(type,id,objId)
{	
	var newCatId = null;

	if (document.getElementById("scatid"+objId).value>0)
	{
		newCatId = document.getElementById("scatid"+objId).value;
	}else if(document.getElementById("tcatid"+objId).value>0){
		newCatId = document.getElementById("tcatid"+objId).value;
	}else  if(document.getElementById("catid"+objId).value>0){
		newCatId = document.getElementById("catid"+objId).value;
	}else{
		return false;
	}
	var url = 'ajax_update.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&type='+type+'&id='+id+'&newCatId='+newCatId;
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest && originalRequest!='0'){

			document.getElementById("catid"+objId).style.display="none";
			document.getElementById("tcatid"+objId).style.display="none";
			document.getElementById("scatid"+objId).style.display="none";
			document.getElementById("show_cat"+objId).innerHTML="<a  href=\"#\" onclick=\"showCat("+objId+");return false;\">"+originalRequest+"</a>";
			document.getElementById("mod"+objId).style.display="none";
		}
	 }
　}

function updateAblumRec(rec,albumId)
{	
	var url = 'ajax_update.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&type=album&id='+albumId+'&newCatId='+rec;
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest){
			if(rec==0){
				document.getElementById("tj_album"+albumId).innerHTML=cancel;
			}else{
				document.getElementById("tj_album"+albumId).innerHTML=recommend;
			}
		}
	}
}
function do_select()
{
	 var box_l = document.getElementsByName("de[]").length;
	 for(var j = 0 ; j < box_l ; j++)
	 {
	  	if(document.getElementsByName("de[]")[j].checked==true)
	  	  document.getElementsByName("de[]")[j].checked = false;
		else
		  document.getElementsByName("de[]")[j].checked = true;
	 }
}
function mouseOver(ob){
	ob.className='mouseover'

}
function mouseOut(ob,className){
	ob.className=className;
}