<?php

namespace App\Services\Providers;

use App\DTO\MovieDetail;
use App\Interfaces\ApiProviderInterface;
use LogadApp\Http\Http;

final class TmdbApiService implements ApiProviderInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://api.themoviedb.org/3';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function formatReleaseDate(string $releaseDate): string
    {
        return date('Y', strtotime($releaseDate));
    }

    private function formatImageUrl(string $image, bool $highRes = false): string
    {
        return 'https://image.tmdb.org/t/p/' .($highRes ? 'original' : 'w500'). $image;
    }

    public function getTrendingMoviesAndShows(): array
    {
        return [
            'movies' => $this->getTrendingMovies(),
            'shows' => $this->getTrendingShows()
        ];
    }

    private function getTrendingMovies(string $period = 'day'): array
    {
        $trending = [];
        $request = Http::get($this->baseUrl .'/trending/movie/' .$period. '?with_original_language=en')
            ->withToken($this->apiKey)
            ->send();

        $response = json_decode($request->body(), true);
        foreach ($response['results'] as $result) {
            $trending[] = [
                'id' => $result['id'],
                'type' => $result['media_type'],
                'title' => $result['title'],
                'overview' => substr($result['overview'], 30),
                'rating' => $result['vote_average'],
                'imageUrl' => 'https://image.tmdb.org/t/p/w500' . $result['poster_path'],
                'releaseYear' =>  date('Y', strtotime($result['release_date']))
            ];
        }

        return $trending;
    }

    private function getTrendingShows(string $period = 'day'): array
    {
        $trending = [];
        $request = Http::get($this->baseUrl .'/trending/tv/' .$period. '?with_original_language=en')
            ->withToken($this->apiKey)
            ->send();

        $response = json_decode($request->body(), true);
        foreach ($response['results'] as $result) {
            $trending[] = [
                'id' => $result['id'],
                'type' => $result['media_type'],
                'title' => $result['name'],
                'overview' => substr($result['overview'], 30),
                'rating' => $result['vote_average'],
                'imageUrl' => 'https://image.tmdb.org/t/p/w500' . $result['poster_path'],
                'releaseYear' =>  date('Y', strtotime($result['first_air_date']))
            ];
        }

        return $trending;
    }

    /**
     * Get details about a movie
     * @param int $id
     */
    public function getMovieDetails(int $id): MovieDetail
    {
        $request = Http::get($this->baseUrl .'/movie/' .$id)
            ->withToken($this->apiKey)
            ->send();

        $response = json_decode($request->body(), true);
        return new MovieDetail(
            id: $response['id'],
            type: 'movie',
            title: $response['title'],
            rating: $response['vote_average'],
            runtime: $response['runtime'],
            tagline: $response['tagline'],
            overview: $response['overview'],
            imageUrl: $this->formatImageUrl($response['poster_path']),
            releaseDate: $response['release_date'],
            backdropUrl: $this->formatImageUrl($response['backdrop_path']),
            releaseYear: $this->formatReleaseDate($response['release_date'])
        );
    }
}