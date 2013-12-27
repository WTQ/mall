<?php
	$find= array();
	$replace=array();
	$banned='/(色情|淫荡)/i';
	$_CACHE['word_filter'] = Array
	(
		'filter' => Array
		(
			'find' => &$find,
			'replace' => &$replace
		),
		'banned' => &$banned
	);
	?>