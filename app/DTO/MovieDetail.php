<?php

declare(strict_types=1);

namespace App\DTO;

use App\Interfaces\DTO;

/**
 * Movie Data Transfer Object
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class MovieDetail implements DTO
{
	public string $type = 'movie';

    public function __construct(
        public int $id,
        public string $title,
        public string $overview,
        public float $rating,
        public string $imageUrl,
        public int $releaseYear,
        public string $releaseDate,
		public ?array $trailer = null,
        public ?string $backdropUrl = null,
        public ?string $tagline = null,
        public int $runtime = 0,
        public array $genres = [],
    )
    {
        $this->rating = round($this->rating, 2);
    }

	public function toArray(): array
	{
		return get_object_vars($this);
	}
}