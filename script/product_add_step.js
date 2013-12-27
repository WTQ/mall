// 分类选择
function selClass(obj){
	$('.wp_category_list').css('background','');
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$("#commoditydd").show();
	$(obj).siblings('li').children('a').attr('class','');
	$(obj).children('a').attr('class','classDivClick');
	tonextClass(obj.id);
}
function tonextClass(text){
	
	var valarray = text.split('|');
	$('#class_id').val(valarray[0]);
	if(valarray[1]==1)
		$('#catid').val(valarray[0]);
	else if(valarray[1]==2)
		$('#tcatid').val(valarray[0]);
	else if(valarray[1]==3)
		$('#scatid').val(valarray[0]);
	else if(valarray[1]==4)
		$('#sscatid').val(valarray[0]);

	$('#dataLoading').show();
	var url = weburl+'/ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&cattype=pro&pcatid='+valarray[0];
	$.post(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		if(originalRequest != "{};")
		{
			$('#button_next_step').attr('disabled',true);
			
			var tempStr = 'var MyMe = ' + originalRequest;
			eval(tempStr);
			var a='';
			var class_div_id = parseInt(valarray[1])+1;
			for(var k in MyMe)
			{
				var Id=MyMe[k][0];
				var Name=MyMe[k][1];
				a+='<li onclick="selClass(this);" id="'+Id+'|'+class_div_id+'"><a href="javascript:void(0)"><span class="has_leaf">'+Name+'</span></a></li>';
			}
			$('#class_div_'+class_div_id).parents('.wp_category_list').removeClass('blank');
			
			for (j=class_div_id; j<=4; j++) {
				$('#class_div_'+(j+1)).parents('.wp_category_list').addClass('blank');
			}
			
			$('#class_div_'+class_div_id).children('ul').empty();
			$('#class_div_'+class_div_id).children('ul').append(a);
			$('#class_div_'+class_div_id).nextAll('div').children('ul').empty();
			
			var str="";
			$.each($('a[class=classDivClick]'),function(i){
				str+=$(this).html()+"&nbsp;&gt;&nbsp;";
			});
			str=str.substring(0,str.length-16);
			$('#commoditydd').html(str);
			$('#dataLoading').hide();
		}
		else
		{
			for(var i= parseInt(valarray[1]); i<4; i++){
				$('#class_div_'+(i+1)).children('ul').empty();
			}
			var str="";
			$.each($('a[class=classDivClick]'),function(i){
				str+=$(this).html()+"&nbsp;&gt;&nbsp;";
			});
			str=str.substring(0,str.length-16);
			$('#commoditydd').html(str);
			disabledButton();
			$('#dataLoading').hide();
		}
	}

}
function disabledButton(){
	if($('#class_id').val() != ''){
		$('#button_next_step').attr('disabled',false).css('cursor','pointer');
	}else {
		$('#button_next_step').attr('disabled',true).css('cursor','auto');
	}
}
// 常用分类选择
function getid(v){
	$('#dataLoading').show();
	$('.wp_category_list').addClass('blank')
	var url = weburl+'/module/product/ajax_update.php';
	var pars = 'action=step1&name='+v.value;
	$.get(url, pars,showResponse);
	function showResponse(originalRequest)
	{
		var tempStr = 'var MyMe = ' + originalRequest; 
		eval(tempStr);
		if (MyMe.done)
		{
			$('.category_list').children('ul').empty();
			if(MyMe.one.length > 0)
			{
				$('#class_div_1').children('ul').append(MyMe.one).parents('.wp_category_list').removeClass('blank');
			}
			if(MyMe.two.length > 0)
			{
				$('#class_div_2').children('ul').append(MyMe.two).parents('.wp_category_list').removeClass('blank');
			}
			if(MyMe.three.length > 0)
			{
				$('#class_div_3').children('ul').append(MyMe.three).parents('.wp_category_list').removeClass('blank');
			}
			if(MyMe.four.length > 0)
			{
				$('#class_div_4').children('ul').append(MyMe.four).parents('.wp_category_list').removeClass('blank');
			}
		}
		else
		{
			$('.wp_category_list').css('background','#E7E7E7 none');
			$(this).parent().css({'background':'#3399FD','color':'#FFF'});
		}
	}
	$('#dataLoading').hide();
	valarray = v.value.split(',');
	if(valarray[0]>0)
	{	
		$('#catid').val(valarray[0]);
		$('#class_id').val(valarray[0]);
	}
	if(valarray[1]>0)
	{	
		$('#tcatid').val(valarray[1]);
		$('#class_id').val(valarray[1]);
	}
	if(valarray[2]>0)
	{	
		$('#scatid').val(valarray[2]);
		$('#class_id').val(valarray[2]);
	}
	if(valarray[3]>0)
	{	
		$('#sscatid').val(valarray[3]);
		$('#class_id').val(valarray[3]);
	}
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$('#commoditydd').show().html(v.options[v.selectedIndex].text);
	disabledButton();
	
}
// ajax查询分类TAG
$('#searchBtn').unbind().click(function(){
										
	$('#searchNone').hide();
	$('#searchSome').hide();
	if($('#searchKey').val() == SEARCHKEY || $('#searchKey').val() == '')
	{
		alert(SEARCHKEY);
	}
	$('.search_result').show();
	$('.sort_block').hide();
	$('.search_title').hide();
	var key = $('#searchKey').val();
	var url = weburl+'/cate_show_ajax.php?oper=ajax&call=search_cate';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&key_word='+key;
	$.post(url,pars,showResponse);
	function showResponse(originalRequest)
	{
		if (originalRequest == "null")
		{
			$('#searchNone').show();
		}
		else
		{
			var tempStr = 'var MyMe = ' + originalRequest; 
			eval(tempStr);
			var tag = '';
			for(var k in MyMe)
			{
				var Name=MyMe[k];
				tag +=('<li nc_type="searchList_name" id="'+k+'">'+Name+'</li>');
			}
			$('#searchSome').show();
			$('#searchList > ul').html(tag);
			$.getScript(weburl+"/script/product_add_step.js");
		}
	}
	
});

// 返回分类选择
$('a[nc_type="return_choose_sort"]').unbind().click(function(){
	$('#class_id').val('');
	$('#catid').val('');
	$('#tcatid').val('');
	$('#scatid').val('');
	$('#sscatid').val('');
	$("#commodityspan").show();
	$("#commoditydt").hide();
	$('#commoditydd').html('');
	$('.search_result').hide();
	$('.sort_block').show();
	$('.search_title').show();
});

// 分类搜索后选择
$('li[nc_type="searchList_name"]').unbind().click(function(){
	var id = $(this).attr('id')
	$('#class_id').val(id);
	var len=id.length;
	if(len>=4)
		$('#catid').val(id.substr(0,4));
    if(len>=6)
		$('#tcatid').val(id.substr(0,6));
	if(len>=8)
		$('#scatid').val(id.substr(0,8));
	if(len==10)
		$('#sscatid').val(id);
	$("#commodityspan").hide();
	$("#commoditydt").show();
	$('#commoditydd').html($(this).html());
	disabledButton();
});