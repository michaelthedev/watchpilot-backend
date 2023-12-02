<?php

declare(strict_types=1);

namespace App\Controllers;

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
        $trending = $this->mediaService
			->getTrending();

        response()->json([
            'error' => false,
            'message' => 'Trending Movies and Tv shows',
            'data' => $trending
        ]);
    }

    public function featured(): void
    {
        $featured = $this->mediaService
			->getFeatured();

        response()->json([
            'error' => false,
            'message' => 'Featured Movies And Shows',
            'data' => $featured
        ]);
    }

    public function airing(): void
    {
        $airing = $this->mediaService
			->getAiring(input('timezone'));

        response()->json([
            'error' => false,
            'message' => 'Airing Today',
            'data' => $airing
        ]);
    }
}
