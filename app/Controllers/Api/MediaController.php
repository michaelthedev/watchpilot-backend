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

    public function tvDetail(int $id): void
    {
		$show = Cache::getOrSet('tvDetail_'.$id, function () use($id) {
			return $this->mediaService
				->getTvDetail($id);
		});

		if (!empty($show)) {
			$this->success('success', $show->toArray());
		} else {
			$this->error('Failed to get show detail');
		}
    }

	public function watchProviders(string $type, int $id): void
	{
		$providers = $this->mediaService
			->watchProviders($type, $id);

		if (!empty($providers)) {
			$this->success('success', $providers);
		} else {
			$this->error('Failed to get watch providers');
		}
	}

	public function related(string $type, int $id): void
	{
		$related = $this->mediaService
			->getRelated($type, $id);

		$this->success('success', $related);
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