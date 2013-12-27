<?php
//===============================================

$shopconfig['menu'][3]=array(
	'menu_show'=>'1','menu_name'=>$lang['pro_list'],'menu_link'=>'product_list','module'=>'product',
);

//=====================管理员后台==================

$mem['product'][1][0]=array(
	'',
	array(
		'prolist.php,1,product,商品管理',
		'product_cat.php,1,product,分类管理',
		'property.php,1,product,属性管理',
		'pro_move.php,1,product,产品转移',
		'module_config.php,1,product,模块设置',
	)
);
$mem['business'][1][0]=array(
	'',
	array(
		'user_order.php,1,product,订单管理',
	)
);

?>