<?php

namespace App\Services;

use App\Services\Providers\TmdbApiService;

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
}