<?php


if (isset($modules_loading) && $modules_loading == true) {

	$i = isset($modules_list) ? count($modules_list) : 0;

	$modules_list[$i]['payment_name'] = basename(__FILE__, '.php');

	$modules_list[$i]['payment_commission'] = '0';

	$modules_list[$i]['author'] = 'b2b-builder';

	$modules_list[$i]['site'] = 'http://www.tenpay.com';

	$modules_list[$i]['version'] = '1.0.0';

	$modules_list[$i]['payment_config'] = array(
		array('name' => 'tenpay_account', 'type' => 'text', 'value' => ''),
		array('name' => 'tenpay_key',     'type' => 'text', 'value' => ''),
		array('name' => 'tenpay_magic_key',   'type' => 'text', 'value' => '')
    );
}
?>