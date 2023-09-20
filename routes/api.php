<?php

use App\Controllers\ApiController;
use Pecee\SimpleRouter\SimpleRouter as Router;

// API Routes start with /api
Router::group(['prefix' => '/api'], function () {
    Router::get('/', [ApiController::class, 'index']);

    Router::get('/getFeaturedAndTrending', [ApiController::class, 'featuredAndTrending']);

    Router::get('/movie/{id}', [ApiController::class, 'movieDetail']);
    Router::get('/tv/{id}', [ApiController::class, 'tvDetail']);

    Router::get('/search', [ApiController::class, 'search']);
});