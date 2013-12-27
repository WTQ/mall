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
function smarty_modifier_explode_one($string,$spr)
{
	$arr=explode($spr,trim($string));
	if($arr[0]>0)
		return $arr[0];
	elseif($arr[1]>0)
		return $arr[1];
	else
		return 0;
}

?>
