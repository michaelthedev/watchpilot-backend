<?php

declare(strict_types=1);

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
        $trending = MediaService::getTrendingMoviesAndShows();

        if (!$trending) {
            response()->json([
                'error' => true,
                'message' => 'Unable to fetch trending movies and shows'
            ]);
        }

        response()->json([
            'error' => false,
            'message' => 'Trending and Featured Movies',
            'data' => [
                'featured' => [],
                'trending' => $trending
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

    /**
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