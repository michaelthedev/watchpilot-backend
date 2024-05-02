<?php
namespace App\Services;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;
use App\Interfaces\ApiProviderInterface;
use Exception;

/**
 * Media Service
 *
 * Serves as a middleman
 * to the actual api provider
 *
 * @package App\Services
 * @author Michael Arawole<michael@logad.net>
 */
final class MediaService
{
	private ApiProviderInterface $provider;

	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$providerClass = config('providers.media_db');

		if (empty($providerClass)) {
			throw new Exception('Invalid API provider');
		}

		$this->provider = new $providerClass();
	}

    public function getTrending(): array
    {
		return $this->provider
			->getTrendingMoviesAndShows();
    }

    public function getFeatured(): array
    {
		return $this->provider
			->getFeaturedMoviesAndShows();
    }

	public function getAiring(?string $timezone = null): array
	{
		return $this->provider
			->getAiring($timezone ?? 'UTC');
	}


    public function getMovieDetail(int $id): MovieDetail
    {
		return $this->provider
			->getMovieDetails($id);
    }

    public function getTvDetail(int $id): TvDetail
    {
		return $this->provider
			->getTvDetails($id);
    }

	public function watchProviders(string $type, int $id): array
	{
		return $this->provider
			->getWatchProviders($type, $id);
	}

	public function getRelated(string $type, int $id): array
	{
		return $this->provider
			->getRelated($type, $id);
	}

    public function search(string $query, string $type): ?array
    {
		return $this->provider
			->search($query, $type);
    }
}