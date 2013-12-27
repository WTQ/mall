<div class="pro_box" style="width:<{$de.width}>px;height:<{$de.height}>px">
	<div class="pro_box_title"><{$de.title}></div>
	<div class="pro_con">
    <{foreach item=list key=num from=$de.list}>
     <li class="pro_img_title">
     	<span class="pro_img">
        <a href="<{$config.weburl}>/?m=product&s=detail&id=<{$list.id}>">
        <{assign var="img" value=$list.id}>
        <{html_image file=uploadfile/comimg/small/$img.jpg alt=$list.pname}>
        </a>
        </span>
        <span><a href="<{$config.weburl}>/?m=product&s=detail&id=<{$list.id}>"><{$list.pname}></a></span>
     </li>
     
    <{/foreach}>
    </div>
</div>