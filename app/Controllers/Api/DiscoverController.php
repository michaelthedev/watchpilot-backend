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
final class DiscoverController extends ApiController
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

	public function filterTrending(string $type): void
	{
		$page = (int) input('page', 1);

		$trending = $this->mediaService
			->getTrending($type, $page);

		$this->success('success', $trending);
	}

    public function featured(?string $type = null): void
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
		$timezone = input('timezone', 'UTC');
		$airing = Cache::getOrSet('discover.airing.'.str_replace(' ', '_', $timezone), function () use($timezone) {
			return $this->mediaService
				->getAiring($timezone);
		}, 86400);


        response()->json([
            'error' => false,
            'message' => 'Airing Today',
            'data' => $airing
        ]);
    }
}
