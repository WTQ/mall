<div class="pro_box" style="width:<{$de.width}>px;height:<{$de.height}>px">
	<div class="pro_box_title"><{$de.title}></div>
	<div class="pro_con">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<{foreach item=list from=$de.list}>
          <tr>
            <td class="pro_title"><a href="<{$config.weburl}>/?m=product&s=detail&id=<{$list.id}>"><{$list.pname}></a></td>
            <td class="pro_time"><{$list.uptime|date_format}></td>
          </tr>
          <{/foreach}>
    </table>
    </div>
</div>