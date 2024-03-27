<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\Cache;
use App\Services\MediaService;

/**
 * Discover Controller
 *
 * Handles requests for /discover/* routes
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class DiscoverController
{
	private MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService();
    }

    public function trending(): void
    {
		$trending = Cache::getOrSet('discover.trending', function () {
			return $this->mediaService
				->getTrending();
		}, 86400);

        response()->json([
            'error' => false,
            'message' => 'Trending Movies and Tv shows',
            'data' => $trending
        ]);
    }

    public function featured(): void
    {
		$featured = Cache::getOrSet('discover.featured', function () {
			return $this->mediaService
				->getFeatured();
		}, 86400);

        response()->json([
            'error' => false,
            'message' => 'Featured Movies And Shows',
            'data' => $featured
        ]);
    }

    public function airing(): void
    {
		$airing = Cache::getOrSet('discover.airing.'.str_replace(' ', '_', input('timezone') ?? ''), function () {
			return $this->mediaService
				->getAiring(input('timezone'));
		}, 86400);


        response()->json([
            'error' => false,
            'message' => 'Airing Today',
            'data' => $airing
        ]);
    }
}
