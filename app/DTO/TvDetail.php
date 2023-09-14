<?php

namespace App\DTO;

/**
 * Tv Show Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class TvDetail
{
    /**
     * @param int $id
     * @param string $type
     * @param string $title
     * @param string $overview
     * @param int $seasons
     * @param float $rating
     * @param string $imageUrl
     * @param int $releaseYear
     * @param string $releaseDate
     * @param string|null $backdropUrl
     * @param string|null $tagline
     * @param string|null $status status of the show (ended, ongoing..)
     */
    public function __construct(
        public int $id,
        public string $type,
        public string $title,
        public string $overview,
        public int $seasons,
        public float $rating,
        public string $imageUrl,
        public int $releaseYear,
        public string $releaseDate,
        public ?string $backdropUrl = null,
        public ?string $tagline = null,
        public ?string $status = null,
        public int $runtime = 0,
    )
    {
        $this->rating = round($this->rating, 2);
    }
}