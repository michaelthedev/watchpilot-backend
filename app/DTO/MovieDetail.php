<?php

namespace App\DTO;

/**
 * MovieDetail Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class MovieDetail
{
    public function __construct(
        public int $id,
        public string $type,
        public string $title,
        public string $overview,
        public float $rating,
        public string $imageUrl,
        public int $releaseYear,
        public string $releaseDate,
        public ?string $backdropUrl = null,
        public ?string $tagline = null,
        public ?string $runtime = null,
    )
    {
        $this->rating = round($this->rating, 2);
    }
}