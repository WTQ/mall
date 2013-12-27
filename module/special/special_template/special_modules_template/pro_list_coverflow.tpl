<link href="<{$config.weburl}>/templates/special_template/images/cover.css" rel="stylesheet" type="text/css" />
<script src="<{$config.weburl}>/templates/special_template/images/cover.js"></script>
<script type="text/javascript"> 
//div ID    , size, zoom, border
imf.create("imageFlow", 0.15, 1.5, 10);
</script>
<div style="float:left; height:400px; background-color:#000000; width:100%;border-bottom:dashed 2px #262626;">
<div id="imageFlow">
	<div class="bank">
    	<{foreach item=list from=$de.list}>
		<a rel="<{$config.weburl}>/uploadfile/comimg/big/<{$list.id}>.jpg" title="<{$list.pname}>" href="<{$config.weburl}>/?m=product&s=detail&id=<{$list.id}>"><{$list.pname}></a>
        <{/foreach}>
	</div>
	<div class="text">
		<div class="title">Loading</div>
		<div class="legend">Please wait...</div>
	</div>
	<div class="scrollbar">
		<img class="track" src="http://www.jscode.cn/jscode/images/08081201sb.gif" alt="">
		<img class="arrow-left" src="http://www.jscode.cn/jscode/images/08081201sl.gif" alt="">
		<img class="arrow-right" src="http://www.jscode.cn/jscode/images/08081201sr.gif" alt="">
		<img class="bar" src="http://www.jscode.cn/jscode/images/08081201sc.gif" alt=""> </div>
</div>
</div>