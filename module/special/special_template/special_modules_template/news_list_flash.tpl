<div class="new_box" style="width:<{$de.width}>px; height:auto;">
	<div class="new_box_title"><{$de.title}></div>
	<div class="new_con">
		<script>
            var url='<{$config.weburl}>';
            var focus_width=<{$de.width}>;
            var focus_height=<{$de.height}>;
            var text_height=20;
            var pics=links=texts='';
            <{foreach item=list key=num from=$de.list}>
				<{if $num>=0}>
					pics+='<{$config.weburl}>/uploadfile/news/<{$list.titlepic}>|';
					links+='<{$list.url|escape:"url"}>|';
					texts+='<{$list.title}>|';
				<{/if}>
             <{/foreach}>
        </script>
        <script src='<{$config.weburl}>/script/slide.js'></script>
    </div>
</div>