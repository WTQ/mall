<?php
	
	$shopconfig['menu'][1]=array(
		'menu_show'=>'1','menu_name'=>$lang['home'],'menu_link'=>''
	);
	$shopconfig['menu'][9]=array(
		'menu_show'=>'1','menu_name'=>$lang['company'],'menu_link'=>'profile','module'=>'shop'
	);
	$shopconfig['menu'][10]=array(
		'menu_show'=>'1','menu_name'=>$lang['credit'],'menu_link'=>'credit','module'=>'shop'
	);
	
	//=====================管理员后台==================
	
	$mem['shop'][1][0]=array(
		'',
		array(
			'shop.php,1,shop,店铺管理',
			'shop_application.php,1,shop,开店申请',
			'shop_grade.php,1,shop,店铺等级',
			'shop_cat.php,1,shop,店铺分类',
			'shop_template.php,1,shop,店铺模版',
			'shop_certification.php,1,shop,店铺认证',
			'module_config.php,1,shop,模块设置',
			'shop_earnest.php,1,shop,保证金',
			'shipping_address.php,1,shop,发货地址',
			'shop_link.php,1,shop,友情链接',
		)
	)
?>