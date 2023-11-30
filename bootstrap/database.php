<?php

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database
$capsule = new Capsule();
$capsule->addConnection([
    'host' => config('database.host'),
    'driver' => config('database.driver'),
    'charset' => config('database.charset'),
    'database' => config('database.name'),
    'username' => config('database.username'),
    'password' => config('database.password'),
    'collation' => config('database.collation'),
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();