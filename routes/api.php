<?php

declare(strict_types=1);

use app\Controllers\Api\ApiController;
use app\Controllers\Api\AuthController;
use app\Controllers\Api\DiscoverController;
use app\Controllers\Api\MediaController;
use app\Controllers\Api\UserController;
use app\Controllers\Api\WatchlistController;
use App\Middlewares\Auth;
use Pecee\SimpleRouter\SimpleRouter as Router;

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
	// header("HTTP/1.1 200 OK");
	die();
}

// API Routes start with /api
Router::group(['prefix' => '/api'], function () {
    Router::get('/ping', [ApiController::class, 'pong']);

    Router::group(['prefix' => '/auth'], function () {
        Router::post('/login', [AuthController::class, 'login']);
        Router::post('/register', [AuthController::class, 'register']);
    });

    Router::group(['prefix' => '/discover'], function () {
        Router::get('/trending', [DiscoverController::class, 'trending']);
        Router::get('/featured', [DiscoverController::class, 'featured']);
        Router::get('/airing', [DiscoverController::class, 'airing']);
    });

    Router::get('/movie/{id}', [MediaController::class, 'movieDetail']);
    Router::get('/tv/{id}', [MediaController::class, 'tvDetail']);

    Router::get('/search', [MediaController::class, 'search']);

	/** Logged in routes */
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
