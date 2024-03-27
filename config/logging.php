<?php

return [
	'channels' => [
		'mediaService' => [
			'driver' => 'daily',
			'path' => LOGS_PATH.'/mediaService/m.log',
			'level' => 'warning',
		],
	]
];