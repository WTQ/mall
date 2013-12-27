<?php
function externalapi($ar)
{
	if(!empty($ar['in'])&&!empty($ar['out']))
		return iconv($ar['in'],$ar['out'],file_get_contents($ar['url']));
	else
		return file_get_contents($ar['url']);
}
?>