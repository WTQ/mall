<?php
//=====================用户店铺==================
$menu['info']['sub'][4]=array(
						'name'=>$lang['album'],
						'type'=>array(1,2),
						'action'=>array(
							'?m=album&s=admin_album_cat'=>$lang['album_cat'],
							'?m=album&s=admin_album'=>$lang['album'],
						),
					);
//=============================================
$mem['shop'][1][1]=array(
	'',
	array(
		'album.php,1,album',
	)
);
?>