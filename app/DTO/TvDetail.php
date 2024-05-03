<?php

namespace App\DTO;

/**
 * Tv Show Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class TvDetail
{
	public string $type = 'tv';

	public function __construct(
        public int $id,
        public string $title,
        public string $overview,
        public array $seasons,
        public float $rating,
        public string $imageUrl,
        public string $releaseYear,
        public string $releaseDate,
        public ?string $backdropUrl = null,
        public ?string $tagline = null,
        public ?string $status = null,
        public int $runtime = 0,
        public array $genres = [],
        public ?TvEpisode $lastEpisode = null,
        public ?TvEpisode $nextEpisode = null,
    )
    {
        $this->rating = round($this->rating, 2);
    }

	public function toArray(): array
	{
		return get_object_vars($this);
	}
}