<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Tv Show Episode Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class TvEpisode
{
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