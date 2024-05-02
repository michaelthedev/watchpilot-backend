<?php

namespace App\Services\Providers;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;
use App\DTO\TvEpisode;
use App\Interfaces\ApiProviderInterface;
use App\Services\Providers\Tmdb\TmdbTransformer;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class TmdbApiService implements ApiProviderInterface
{
	private Client $client;
	private TmdbTransformer $transformer;

	public function __construct()
    {
		// set transformer
		$this->transformer = new TmdbTransformer();

		$this->client = new Client([
			'base_uri' => config('tmdb.base_url').'/',
			'headers' => [
				'Authorization' => 'Bearer '.config('tmdb.api_key')
			],
		]);
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

    public function getFeaturedMoviesAndShows(): array
    {
        return [
            'movies' => $this->getFeaturedMovies(),
            'shows' => $this->getFeaturedShows()
        ];
    }

    private function getFeaturedMovies(): array
    {
        $featured = [];
		$request = $this->client->get('discover/movie', [
			'query' => [
				'with_original_language' => 'en',
				'sort_by' => 'popularity.desc',
				'with_release_type' => '2|3'
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $featured[] = [
                'id' => $result['id'],
                'type' => 'movie',
                'title' => htmlentities($result['title']),
                'overview' => htmlentities(substr($result['overview'], 30)),
                'rating' => $result['vote_average'],
                'imageUrl' => 'https://image.tmdb.org/t/p/w500' . $result['poster_path'],
                'releaseYear' =>  date('Y', strtotime($result['release_date']))
            ];
        }

        return $featured;
    }

    private function getFeaturedShows(): array
    {
        $featured = [];
		$request = $this->client->get('discover/tv', [
			'query' => [
				'with_original_language' => 'en',
				'sort_by' => 'popularity.desc'
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $featured[] = [
                'id' => $result['id'],
                'type' => 'tv',
                'title' => htmlentities($result['name']),
                'overview' => htmlentities(substr($result['overview'], 30)),
                'rating' => $result['vote_average'],
                'imageUrl' => 'https://image.tmdb.org/t/p/w500' . $result['poster_path'],
                'releaseYear' =>  date('Y', strtotime($result['first_air_date']))
            ];
        }

        return $featured;
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
		$request = $this->client->get('trending/movie/'.$period, [
			'query' => [
				'with_original_language' => 'en'
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $trending[] = [
                'id' => $result['id'],
                'type' => $result['media_type'],
                'title' => htmlentities($result['title']),
                'overview' => htmlentities(substr($result['overview'], 30)),
                'rating' => $result['vote_average'],
				'imageUrl' => $this->formatImageUrl($result['poster_path']),
                'releaseYear' =>  date('Y', strtotime($result['release_date']))
            ];
        }

        return $trending;
    }

    private function getTrendingShows(string $period = 'day'): array
    {
        $trending = [];
		$request = $this->client->get('trending/tv/'.$period, [
			'query' => [
				'with_original_language' => 'en'
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $trending[] = [
                'id' => $result['id'],
                'type' => $result['media_type'],
                'title' => htmlentities($result['name']),
                'overview' => htmlentities(substr($result['overview'], 30)),
                'rating' => $result['vote_average'],
                'imageUrl' => 'https://image.tmdb.org/t/p/w500' . $result['poster_path'],
                'releaseYear' =>  date('Y', strtotime($result['first_air_date']))
            ];
        }

        return $trending;
    }

	private function getAiringShows(string $timezone): array
    {
        $aring = [];

		// get date based on timezone
		$date = Carbon::now($timezone);

		// get beginning and end of week
		$beginningOfWeek = $date->startOfWeek()->format('Y-m-d');
		$endOfWeek = $date->endOfWeek()->format('Y-m-d');

		$request = $this->client->get('discover/tv', [
			'query' => [
				'air_date.gte' => $beginningOfWeek,
				'air_date.lte' => $endOfWeek,
				'sort_by' => 'popularity.desc',
				'with_original_language' => 'en',
				'timezone' => $timezone
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $aring[] = [
                'id' => $result['id'],
                'type' => 'tv',
                'title' => htmlentities($result['name']),
                'overview' => htmlentities(substr($result['overview'], 0,100)),
                'rating' => $result['vote_average'],
                'imageUrl' => $this->formatImageUrl($result['poster_path']),
                'releaseYear' => date('Y', strtotime($result['first_air_date']))
            ];
        }

        return $aring;
    }

	private function getAiringMovies(string $timezone): array
    {
        $aring = [];

		// get date based on timezone
		$date = Carbon::now($timezone);

		// get beginning and end of week
		$beginningOfWeek = $date->startOfWeek()->format('Y-m-d');
		$endOfWeek = $date->endOfWeek()->format('Y-m-d');

		$request = $this->client->get('discover/movie', [
			'query' => [
				'air_date.gte' => $beginningOfWeek,
				'air_date.lte' => $endOfWeek,
				'sort_by' => 'popularity.desc',
				'with_original_language' => 'en',
				'timezone' => $timezone
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        foreach ($response['results'] as $result) {
            $aring[] = [
                'id' => $result['id'],
                'type' => 'movie',
				'title' => htmlentities($result['title']),
				'overview' => htmlentities(substr($result['overview'], 0, 100)),
				'rating' => $result['vote_average'],
                'imageUrl' => $this->formatImageUrl($result['poster_path']),
				'releaseYear' =>  date('Y', strtotime($result['release_date']))
            ];
        }

        return $aring;
    }

	public function getAiring(string $timezone): array
	{
		return [
			'movies' => $this->getAiringMovies($timezone),
			'shows' => $this->getAiringShows($timezone),
		];
	}

    /**
     * Get details about a movie
     * @param int $id
     * @return MovieDetail
     * @throws Exception|GuzzleException
	 */
    public function getMovieDetails(int $id): MovieDetail
    {
		$request = $this->client->get('movie/'.$id, [
			'query' => [
				'append_to_response' => 'videos'
			]
		]);

        $response = json_decode($request->getBody()
			->getContents(), true);

		return $this->transformer
			->transform($response)
			->to('movie');
    }

    public function getTvDetails(int $id): TvDetail
    {
		$request = $this->client->get('tv/'.$id);

        $response = json_decode($request->getBody()
			->getContents(), true);

		return $this->transformer
			->transform($response)
			->to('tv');
    }

    public function search(string $query, string $type): array
    {
        $type = match ($type) {
            'all' => 'multi',
            default => $type,
        };

		$request = $this->client->get('search/'.$type, [
			'query' => [
				'query' => $query
			]
		]);

        $response = json_decode($request->getBody()->getContents(), true);
        $results = [];
        foreach ($response['results'] as $result) {
            if (empty($result['poster_path'])) continue;
            $results[] = [
                'id' => $result['id'],
                'type' => $result['media_type'] ?? $type,
                'title' => $result['title'] ?? $result['name'],
                'overview' => substr($result['overview'], 30),
                'rating' => $result['vote_average'],
                'imageUrl' => $this->formatImageUrl($result['poster_path']),
                'releaseYear' =>  date('Y', strtotime($result['release_date'] ?? $result['first_air_date']))
            ];
        }

        return $results;
    }
}

