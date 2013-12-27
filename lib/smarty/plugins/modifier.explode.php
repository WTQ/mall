<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_explode($string,$spr)
{
	$arr=explode($spr,trim($string));
	$arr2=array();
	foreach($arr as $v)
	{
		$sar=explode("=",$v);
		if(!empty($sar[1]))
			$arr2[$sar[0]]=$sar[1];
	}
	return $arr2;
}

?>
