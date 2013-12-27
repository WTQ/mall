var handle_pic, handle;
$('*[nc_type="handle_image"]').unbind().each(function(){
	$(this).unbind();
	
	if($(this).find('img:first').prev().val() != ''){
		$(this).hover(function(){
			handle = $(this).find('*[nc_type="handle"]');
			handle.show();
			handle.hover(function(){
				set_zindex($(this), "999");
			},
			function(){
				set_zindex($(this), "0");
			});
			set_zindex($(this), '999');
		},
		function(){
			handle.hide();
			set_zindex($(this), '0');
		});
	}
});

$('span[nctype="left"]').unbind().click(function(){
	var obj= $(this).parents('li');
	if(obj.prev().html() != null){
		obj.insertBefore(obj.prev());
		obj.find('*[nc_type="handle"]').hide();
		set_zindex(obj, '0');
	}
});

$('span[nctype="right"]').unbind().click(function(){
	var obj= $(this).parents('li');
	if(obj.next().html() != null){
		obj.insertAfter(obj.next());
		obj.find('*[nc_type="handle"]').hide();
		set_zindex(obj, '0');
	}
});

$('span[nctype="delete_image"]').unbind().click(function(){
	$(this).parents('li')
		.find('img:first').attr('src','image/default/user_admin/loading.gif')
		.end().find('*[nc_type="handle"]').hide()
		.end().find('input[type="hidden"]').val('')
		.end().find('img:first').attr('src','image/default/nopicsmall.gif');
		$.getScript("script/product.js");
});

function set_zindex(parents, index){
    $.each(parents,function(i,n){
        if($(n).css('position') == 'relative'){
           $(n).css('z-index',index);
        }
    });
}