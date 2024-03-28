<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\Cache;
use App\Services\MediaService;

/**
 * Media Controller
 *
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class MediaController extends ApiController
{
	private MediaService $mediaService;

	public function __construct()
	{
		$this->mediaService = new MediaService();
	}

    /**
     * Fetch Details about a movie
     * @param int $id Movie Id
     * @return void
     */
    public function movieDetail(int $id): void
    {
		$movie = Cache::getOrSet('movieDetail_'.$id, function () use($id) {
			return $this->mediaService
				->getMovieDetail($id);
		});

		if (!empty($movie)) {
			$this->success('success', $movie->toArray());
		} else {
			$this->error('Failed to get movie detail');
		}
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
            'data' => $this->mediaService
				->getTvDetail($id)
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