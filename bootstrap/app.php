<?php

declare(strict_types=1);

use App\Core\Application;

define("BASE_PATH", dirname(__FILE__, 2));

require __DIR__.'/constants.php';

$app = new Application(
	BASE_PATH
);

$app->loadRoutes([
	ROUTES_PATH . '/web.php',
	ROUTES_PATH . '/api.php',
	ROUTES_PATH . '/admin.php'
]);

return $app;
