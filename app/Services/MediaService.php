<?php
namespace App\Services;

use App\DTO\MovieDetail;
use App\Interfaces\ApiProviderInterface;
use App\Services\Providers\TmdbApiService;

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
    private static function getProvider(): ApiProviderInterface
    {
        $currentProvider = config('app.apiProvider');
        $providers = config('app.providers');
        $apiKey = $providers[$currentProvider]['apiKey'] ?? null;

        return match ($currentProvider) {
            'tmdb' => new TmdbApiService($apiKey),
            default => throw new \Exception('Invalid API provider'),
        };
    }

    public static function getTrendingMoviesAndShows(): array
    {
        try {
            $provider = self::getProvider();
            return $provider
                ->getTrendingMoviesAndShows();
        } catch (\Exception) {
            return [];
        }
    }

    public static function getMovieDetail(int $id): ?MovieDetail
    {
        try {
            $provider = self::getProvider();
            return $provider
                ->getMovieDetails($id);
        } catch (\Exception) {
             return null;
        }
    }
}