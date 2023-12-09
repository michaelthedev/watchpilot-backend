<?php

declare(strict_types=1);

// Application base path
use App\Services\Cache;

define("BASE_PATH", dirname(__FILE__, 2));

// Include the composer autoloader
require BASE_PATH.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Misc
require __DIR__.'/constants.php';
require __DIR__.'/database.php';
require __DIR__.'/dependencies.php';

// Default timezone
date_default_timezone_set(config('app.timezone'));

Cache::boot();

