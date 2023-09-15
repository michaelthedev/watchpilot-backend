<?php

namespace App\Services\Providers;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;
use App\DTO\TvEpisode;
use App\Interfaces\ApiProviderInterface;
use Exception;
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

    private function formatImageUrl(?string $image, bool $highRes = false): ?string
    {
        if (empty($image)) return null;
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
     * @return MovieDetail
     * @throws Exception
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
            genres: $response['genres'],
            rating: $response['vote_average'],
            runtime: $response['runtime'],
            tagline: $response['tagline'],
            overview: $response['overview'],
            imageUrl: $this->formatImageUrl($response['poster_path']),
            releaseDate: $response['release_date'],
            backdropUrl: $this->formatImageUrl($response['backdrop_path'], true),
            releaseYear: $this->formatReleaseDate($response['release_date'])
        );
    }

    /**
     * Get details about a movie
     * @param int $id
     * @return TvDetail
     * @throws Exception
     */
    public function getTvDetails(int $id): TvDetail
    {
        $request = Http::get($this->baseUrl .'/tv/' .$id)
            ->withToken($this->apiKey)
            ->send();

        $response = json_decode($request->body(), true);

        // Last episode
        $lastEpisodeDto = $this->getEpisodeDto($response['last_episode_to_air']);

        // Next episode
        $nextEpisodeDto = $this->getEpisodeDto($response['next_episode_to_air']);

        return new TvDetail(
            id: $response['id'],
            type: 'tv',
            title: $response['name'],
            genres: $response['genres'],
            rating: $response['vote_average'],
            status: $response['status'],
            runtime: $response['episode_run_time'][0] ?? 0,
            seasons: $response['number_of_seasons'],
            tagline: $response['tagline'],
            overview: $response['overview'],
            imageUrl: $this->formatImageUrl($response['poster_path']),
            releaseDate: $response['first_air_date'],
            backdropUrl: $this->formatImageUrl($response['backdrop_path'], true),
            releaseYear: $this->formatReleaseDate($response['first_air_date']),
            lastEpisode: $lastEpisodeDto,
            nextEpisode: $nextEpisodeDto,
        );
    }

    /**
     * @param ?array $episode
     * @return TvEpisode|null
     */
    private function getEpisodeDto(?array $episode): ?TvEpisode
    {
        if (empty($episode)) {
            $episodeDto = null;
        } else {
            $episodeDto = new TvEpisode(
                id: $episode['id'],
                title: $episode['name'],
                rating: $episode['vote_average'],
                season: $episode['season_number'],
                runtime: $episode['runtime'],
                episode: $episode['episode_number'],
                overview: $episode['overview'],
                imageUrl: $this->formatImageUrl($episode['still_path']),
                releaseDate: $episode['air_date'],
            );
        }
        return $episodeDto;
    }
}