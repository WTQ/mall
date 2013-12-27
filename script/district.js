// JavaScript Document
function sd()
{
	$("#d_1").attr('class','hidden')
	$("#d_2").removeClass('hidden')
}
function selClass(obj){
	
	$(obj).children('option').attr('class','');
	if(!obj.value)
	{
		$('#'+obj.id).nextAll('select').empty();
		$('#'+obj.id).nextAll('select').addClass('hidden');
		$('#t').val('');
		return false;	
	}
	tonextClass(obj.value);
}
function tonextClass(value){
	var valarray = value.split('|');
	var url = weburl+'/ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&d_id='+valarray[0];
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest == "")
		{
			for(var i= parseInt(valarray[1]); i<4; i++){
				$('#select_'+(i+1)).empty();
				$('#select_'+(i+1)).addClass('hidden');
			}
			var str="";
			$('#id_'+valarray[1]).val(valarray[0]);
			$.each($('option[value='+value+']'),function(i){
				$(this).attr('class','classClick');
			});
			$.each($('option[class="classClick"]'),function(i){
				str+=$(this).text()+" ";
			});
			str=str.substring(0,str.length-1);
			$('#t').val(str);
			
		}
		
		var tempStr = 'var MyMe = ' + originalRequest;
		eval(tempStr);
		var a='<option value="">--请选择--</option>';
		var class_div_id = parseInt(valarray[1])+1;
		for(var k in MyMe)
		{
			var Id=MyMe[k][0];
			var Name=MyMe[k][1];
			a+='<option value="'+Id+'|'+class_div_id+'">'+Name+'</option>';
		}
		
		$('#select_'+class_div_id).removeClass('hidden');
		
		$('#id_'+valarray[1]).val(valarray[0]);
		for (j=class_div_id; j<=4; j++) {
			$('#select_'+(j+1)).addClass('hidden');
			$('#id_'+j).val('');
		}
		
		$('#select_'+class_div_id).empty();
		$('#select_'+class_div_id).append(a);
		$('#select_'+class_div_id).nextAll('select').empty();
		var str="";
		$.each($('option[value='+value+']'),function(i){
			$(this).attr('class','classClick');
		});
		$.each($('option[class="classClick"]'),function(i){
			str+=$(this).text()+" ";
		});
		str=str.substring(0,str.length-1);
		$('#t').val(str);
			
	}
}