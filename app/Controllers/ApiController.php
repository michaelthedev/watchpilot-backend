<?php
namespace App\Controllers;

use App\Services\MediaService;

/**
 * API Controller
 *
 * Handles requests for /api/* routes
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class ApiController
{
    public function index(): void
    {
        $appName = config('app.name');
        response()->json([
            'error' => false,
            'message' => 'Welcome to '.$appName.' API'
        ]);
    }

    public function featuredAndTrending(): void
    {
        response()->json([
            'error' => false,
            'message' => 'Trending and Featured Movies',
            'data' => [
                'featured' => [],
                'trending' => MediaService::getTrendingMoviesAndShows(),
            ]
        ]);
    }

    public function movieDetail(int $id): void
    {
        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => MediaService::getMovieDetail($id)
        ]);
    }
}