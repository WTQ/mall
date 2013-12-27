<?php

if (isset($modules_loading) && $modules_loading == true) {

	$i = isset($modules_list) ? count($modules_list) : 0;

	$modules_list[$i]['payment_name'] = basename(__FILE__, '.php');

	$modules_list[$i]['payment_desc'] = 'alipay_desc';

	$modules_list[$i]['payment_commission'] = '0';

	$modules_list[$i]['author'] = 'b2b-builder';

	$modules_list[$i]['site'] = 'http://www.alipay.com';

	$modules_list[$i]['version'] = '1.0.0';

	$modules_list[$i]['payment_config']  = array(
		array('name' => 'seller_email',    'type' => 'text', 'value' => ''),
		array('name' => 'key',        'type' => 'text', 'value' => ''),
		array('name' => 'partner',    'type' => 'text', 'value' => ''),
		//array('name' => 'alipay_interface', 'type' => 'select', 'value' => '')
	);
}
?>