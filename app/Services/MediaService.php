<?php
namespace App\Services;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;
use App\Interfaces\ApiProviderInterface;
use App\Services\Providers\TmdbApiService;
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
class MediaService
{
	private ApiProviderInterface $provider;

	/**
	 * @throws Exception
	 */
	public function __construct()
	{
		$currentProvider = config('app.apiProvider');
		$providers = config('app.providers');
		$apiKey = $providers[$currentProvider]['apiKey'] ?? null;

		$this->provider = match ($currentProvider) {
			'tmdb' => new TmdbApiService($apiKey),
			default => throw new Exception('Invalid API provider'),
		};
	}

    public function getTrending(): array
    {
        try {
            return $this->provider
                ->getTrendingMoviesAndShows();
        } catch (Exception) {
            return [];
        }
    }

    public function getFeatured(): array
    {
        try {
            return $this->provider
                ->getFeaturedMoviesAndShows();
        } catch (Exception) {
            return [];
        }
    }

	public function getAiring(?string $timezone = null): array
	{
		$timezone = $timezone ?? 'UTC';
		try {
			return $this->provider
				->getAiring($timezone);
		} catch (Exception) {
			return [];
		}
	}


    public function getMovieDetail(int $id): ?MovieDetail
    {
        try {
            $provider = $this->provider;
            return $provider
                ->getMovieDetails($id);
        } catch (Exception) {
             return null;
        }
    }

    public function getTvDetail(int $id): ?TvDetail
    {
        try {
            $provider = $this->provider;
            return $provider
                ->getTvDetails($id);
        } catch (Exception) {
             return null;
        }
    }

    public function search(string $query, string $type): ?array
    {
        try {
            return $this->provider
                ->search($query, $type);
        } catch (Exception) {
             return null;
        }
    }
}