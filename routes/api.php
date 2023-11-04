<?php

declare(strict_types=1);

use App\Controllers\ApiController;
use App\Controllers\AuthController;

use Pecee\SimpleRouter\SimpleRouter as Router;

// API Routes start with /api
Router::group(['prefix' => '/api'], function () {
    Router::get('/', [ApiController::class, 'index']);

    Router::get('/getFeaturedAndTrending', [ApiController::class, 'featuredAndTrending']);

    Router::get('/movie/{id}', [ApiController::class, 'movieDetail']);
    Router::get('/tv/{id}', [ApiController::class, 'tvDetail']);

    Router::get('/search', [ApiController::class, 'search']);

    Router::group(['prefix' => '/auth'], function () {
        Router::post('/login', [\App\Controllers\AuthController::class, 'login']);
        Router::post('/register', [\App\Controllers\AuthController::class, 'register']);
    });
});