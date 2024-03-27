<?php

return [
	'channels' => [
		'test' => [
			'driver' => 'daily',
			'path' => LOGS_PATH.'/test.log',
			'level' => 'warning',
		],
	]
];