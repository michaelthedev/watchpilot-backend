<?php

declare(strict_types=1);

namespace App\Services\Providers\Tmdb;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;
use App\DTO\TvEpisode;
use App\DTO\TvSeason;
use App\Interfaces\Transformers\MediaDataTransformer;
use DateTimeImmutable;

final class TmdbTransformer
	implements MediaDataTransformer
{
	private array $data;
	
	public function transform(array $data): self
	{
		$this->data = $data;
		return $this;
	}

	public function to(string $type): MovieDetail|array|TvDetail|TvSeason
	{
		return match ($type) {
			'movie' => $this->transformMovie($this->data),
			'tv' => $this->transformTv($this->data),
			'season' => $this->transformSeason($this->data),
			'movieSummary' => $this->transformMovieSummary($this->data),
			'tvSummary' => $this->transformTvSummary($this->data),
			default => throw new \Exception('Invalid type')
		};
	}
	
	private function transformMovie(array $data): MovieDetail
	{
		return new MovieDetail(
			id: $data['id'],
			title: $data['title'],
			genres: $data['genres'],
			rating: $data['vote_average'],
			runtime: $data['runtime'],
			tagline: $data['tagline'],
			trailers: $this->findTrailerFromVideos($data['videos']['results'] ?? []),
			overview: $data['overview'],
			imageUrl: $this->formatImageUrl($data['poster_path']),
			releaseDate: $data['release_date'],
			backdropUrl: $this->formatImageUrl($data['backdrop_path'], true),
			releaseYear: (int) $this->formatReleaseDate($data['release_date'])
		);
	}

	private function transformMovieSummary(array $data): array
	{
		return [
			'id' => $data['id'],
			'type' => 'movie',
			'title' => htmlentities($data['title']),
			'overview' => htmlentities($data['overview']),
			'rating' => round($data['vote_average'], 2, PHP_ROUND_HALF_DOWN),
			'imageUrl' => $this->formatImageUrl($data['poster_path']),
			'releaseYear' =>  $this->formatReleaseDate($data['release_date']),
		];
	}

	private function transformTv(array $data): TvDetail
	{
		return new TvDetail(
			id: $data['id'],
			title: $data['name'],
			genres: $data['genres'],
			rating: $data['vote_average'],
			status: $data['status'],
			runtime: $data['episode_run_time'][0] ?? 0,
			seasons_count: $data['number_of_seasons'],
			seasons: $this->getSeasons($data['seasons']),
			trailers: $this->findTrailerFromVideos($data['videos']['results'] ?? []),
			tagline: $data['tagline'],
			overview: $data['overview'],
			imageUrl: $this->formatImageUrl($data['poster_path']),
			releaseDate: $data['first_air_date'],
			backdropUrl: $this->formatImageUrl($data['backdrop_path'], true),
			releaseYear: $this->formatReleaseDate($data['first_air_date']),
			lastEpisode: $this->getEpisodeDto($data['last_episode_to_air']),
			nextEpisode: $this->getEpisodeDto($data['next_episode_to_air']),
		);
	}

	private function transformTvSummary(array $data): array
	{
		return [
			'id' => $data['id'],
			'type' => 'tv',
			'title' => htmlentities($data['name']),
			'overview' => htmlentities($data['overview'], 30),
			'rating' => round($data['vote_average'], 2, PHP_ROUND_HALF_DOWN),
			'imageUrl' => $this->formatImageUrl($data['poster_path']),
			'releaseYear' =>  $this->formatReleaseDate($data['first_air_date']),
		];
	}

	private function transformSeason(array $data): TvSeason
	{
		$episodes = [];
		foreach ($data['episodes'] as $episode) {
			$episodes[] = $this->getEpisodeDto($episode);
		}

		if (!empty($data['videos']['results'])) {
			$trailers = $this->findTrailerFromVideos($data['videos']['results']);
		}

		return new TvSeason(
			id: $data['id'],
			title: $data['name'],
			number: $data['season_number'],
			rating: $data['vote_average'],
			episodes: $episodes,
			trailers: $trailers ?? [],
			imageUrl: $this->formatImageUrl($data['poster_path']),
			overview: $data['overview'],
			releaseDate: $data['air_date'],
		);
	}

	private function getSeasons(array $seasons): array
	{
		$data = [];
		foreach ($seasons as $season) {
			$data[] = new TvSeason(
				id: (int) $season['id'],
				title: $season['name'],
				number: $season['season_number'],
				rating: $season['vote_average'],
				imageUrl: $this->formatImageUrl($season['poster_path']),
				overview: $season['overview'],
				releaseDate: $season['air_date'],
			);
		}

		return $data;
	}

	/**
	 * @param ?array $episode
	 * @return TvEpisode|null
	 */
	private function getEpisodeDto(?array $episode): ?TvEpisode
	{
		if (empty($episode)) {
			return null;
		}

		return new TvEpisode(
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

	private function formatReleaseDate(string $releaseDate, string $format = 'Y'): string
	{
		return (new DateTimeImmutable($releaseDate))
			->format($format);
	}

	public function formatImageUrl(?string $image, bool $highRes = false): ?string
	{
		if (empty($image)) return null;
		return 'https://image.tmdb.org/t/p/' .($highRes ? 'original' : 'w500'). $image;
	}

	private function getTrailer(int $id): ?array
	{
		return null;
	}

	private function findTrailerFromVideos(array $videos): array
	{
		$trailers = [];
		foreach ($videos as $video) {
			if ($video['type'] == 'Trailer') {
				$trailers[] = [
					'key' => $video['key'],
					'name' => $video['name'],
					'site' => $video['site'],
				];
			}
		}

		return $trailers;
	}
}