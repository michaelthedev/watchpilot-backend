<?php

return [
	'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
	'stores' => [
		'file' => [
			'driver' => 'file',
			'path' => CACHE_PATH,
		],
		'redis' => [
			'driver' => 'redis',
			'connection' => 'default',
		],
	],

];