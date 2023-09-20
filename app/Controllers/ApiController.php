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


    /**
     * Fetch Details about a movie
     * @param int $id Movie Id
     * @return void
     */
    public function movieDetail(int $id): void
    {
        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => MediaService::getMovieDetail($id)
        ]);
    }

    /***
     * Fetch details about a tv show
     * @param int $id Tv show id
     * @return void
     */
    public function tvDetail(int $id): void
    {
        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => MediaService::getTvDetail($id)
        ]);
    }

    public function search(): void
    {
        validate([
            'query' => 'required',
            'type' => 'required|in:tv,movie,all'
        ], input()->getOriginalParams());

        $query = input()->get('query');
        $type = input()->get('type');

        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => MediaService::search($query, $type)
        ]);
    }
}