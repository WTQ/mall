(function($) {
	var pagenum = 1;   //延迟加载翻页，默认设为第 1 页
	$.fn.lazyinit = function(options){
		var dataContainer = $(this);
		//实现好友动态分页		
		$(this).find('.more').find('a').live('click',function(){
			var url = $(this).attr('nc_url');
			$(dataContainer).lazyshow({url:url,iIntervalId:false});
		});
	}
	$.fn.lazyshow = function(options) {
		
		var settings = {
            iIntervalId:true
        };
		//异步加载的页面url
		var loadurl = options.url;
		//初始化时
		settings.iIntervalId = options.iIntervalId;
		//列表容器
		var dataContainer = $(this);
		//如果临时列表模块不存在，则追加一个该模块
		if(!$(dataContainer).next("#tmp")[0]){
			$(dataContainer).after('<div id="tmp"></div>');
		}
		//初始化时延时分页为1
		if(settings.iIntervalId){
			pagenum = 1;
		}
		//删除加载更多的连接
		$("#more").remove();
		//加载好友动态
		
		$("#tmp").load(loadurl+'&page='+pagenum,'',function(){
			
			//获取load的html追加到列表中，同时清除临时列表
			var html = '';
			html += $("#module").html();
			if(settings.iIntervalId === false){
				$(dataContainer).append(html);
			}else{
				$(dataContainer).html(html);
			}
			$("#tmp").remove();
			
			//修改加载更多的html
			$(dataContainer).find("#more").html('<div class="more"><a href="javascript:void(0);" nc_url="'+loadurl+'">查看更多动态</a></div>');
			
		});
		pagenum++;//延时分页自增一
    };
})(jQuery);