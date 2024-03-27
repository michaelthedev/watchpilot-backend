<?php

declare(strict_types=1);

use App\Core\Application;

define("BASE_PATH", dirname(__FILE__, 2));

require __DIR__.'/constants.php';

$app = new Application(
	BASE_PATH
);

return $app;
