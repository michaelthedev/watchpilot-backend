<?php

namespace App\DTO;

/**
 * Tv Show Episode Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
class TvEpisode
{
    /**
     * @param int $id
     * @param string $title
     * @param string $overview
     * @param float $rating
     * @param ?string $imageUrl
     * @param int $season
     * @param int $episode
     * @param string $releaseDate
     * @param ?int $runtime
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $overview,
        public float $rating,
        public ?string $imageUrl,
        public int $season,
        public int $episode,
        public string $releaseDate,
        public ?int $runtime = 0,
    )
    {
        $this->rating = round($this->rating, 2);
    }
}