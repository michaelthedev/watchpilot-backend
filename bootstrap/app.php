<?php

declare(strict_types=1);

// Application base path
define("BASE_PATH", dirname(__FILE__, 2));

// Include the composer autoloader
require BASE_PATH.'/vendor/autoload.php';

// Misc
require __DIR__.'/constants.php';
require __DIR__.'/database.php';
require __DIR__.'/dependencies.php';

// Default timezone
date_default_timezone_set(config('app.timezone'));

