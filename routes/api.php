<?php

declare(strict_types=1);

use App\Controllers\ApiController;
use App\Controllers\AuthController;

use App\Controllers\UserController;
use App\Controllers\WatchlistController;
use App\Middlewares\Auth;
use Pecee\SimpleRouter\SimpleRouter as Router;

// API Routes start with /api
Router::group(['prefix' => '/api'], function () {
    Router::get('/', [ApiController::class, 'index']);

    Router::group(['prefix' => '/auth'], function () {
        Router::post('/login', [AuthController::class, 'login']);
        Router::post('/register', [AuthController::class, 'register']);
    });

    Router::get('/getFeaturedAndTrending', [ApiController::class, 'featuredAndTrending']);

    Router::get('/movie/{id}', [ApiController::class, 'movieDetail']);
    Router::get('/tv/{id}', [ApiController::class, 'tvDetail']);

    Router::get('/search', [ApiController::class, 'search']);

    Router::group(['middleware' => Auth::class], function () {
        Router::group(['prefix' => '/user'], function () {
            Router::get('/', [UserController::class, 'index']);
            Router::post('/update', [UserController::class, 'update']);
        });

        Router::get('/watchlists', [WatchlistController::class, 'index']);
        Router::post('/watchlists', [WatchlistController::class, 'store']);
    });
});