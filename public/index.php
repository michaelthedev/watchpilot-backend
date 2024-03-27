<?php

declare(strict_types=1);

// Include the composer autoloader
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$app->boot(true);