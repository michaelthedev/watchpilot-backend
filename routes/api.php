<?php

declare(strict_types=1);

use App\Controllers\ApiController;
use App\Controllers\AuthController;

use App\Controllers\DiscoverController;
use App\Controllers\UserController;
use App\Controllers\WatchlistController;
use App\Middlewares\Auth;

use Pecee\SimpleRouter\SimpleRouter as Router;

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

// API Routes start with /api
Router::group(['prefix' => '/api'], function () {
    Router::get('/', [ApiController::class, 'index']);

    Router::group(['prefix' => '/auth'], function () {
        Router::post('/login', [AuthController::class, 'login']);
        Router::post('/register', [AuthController::class, 'register']);
    });

    Router::group(['prefix' => '/discover'], function () {
        Router::get('/trending', [DiscoverController::class, 'trending']);
        Router::get('/featured', [DiscoverController::class, 'featured']);
        Router::get('/airing', [DiscoverController::class, 'airing']);
    });

    Router::get('/movie/{id}', [ApiController::class, 'movieDetail']);
    Router::get('/tv/{id}', [ApiController::class, 'tvDetail']);

    Router::get('/search', [ApiController::class, 'search']);

    Router::group(['middleware' => Auth::class], function () {

        Router::group(['prefix' => '/user'], function () {
            Router::get('/', [UserController::class, 'index']);
            Router::post('/update', [UserController::class, 'update']);
        });

        Router::group(['prefix' => '/watchlists'], function () {
            Router::get('/', [WatchlistController::class, 'index']);
            Router::post('/', [WatchlistController::class, 'store']);

            Router::get('/{watchlist_id}', [WatchlistController::class, 'get']);
            Router::delete('/{watchlist_id}', [WatchlistController::class, 'destroy']);
            Router::patch('/{watchlist_id}', [WatchlistController::class, 'update']);
            Router::put('/{watchlist_id}', [WatchlistController::class, 'storeItem']);
        });
    });
});
