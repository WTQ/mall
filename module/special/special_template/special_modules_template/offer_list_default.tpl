<div>
	<div><{$de.title}></div>
	<div>
        <url>
        	<{foreach item=list from=$de.list}>
            	<li><a href="shop.php?uid=<{$list.userid}>action=infod&id=<{$list.id}>"><{$list.title}></a></li>
            <{/foreach}>
        </ul>
    </div>
</div>