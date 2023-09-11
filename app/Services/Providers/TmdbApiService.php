<?php

namespace App\Services\Providers;

use App\Services\ApiProviderInterface;
use LogadApp\Http\Http;

final class TmdbApiService implements ApiProviderInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getTrendingMoviesAndShows(): array
    {
        $request = Http::get($this->baseUrl .'/trending/all/day?with_original_language=en')
            ->withToken($this->apiKey)
            ->send();

        $response = json_decode($request->body(), true);
        foreach ($response['results'] as &$result) {
            $result['imageUrl'] = 'https://image.tmdb.org/t/p/w500' . $result['poster_path'];
        }

        return $response['results'] ?? [];
    }
}