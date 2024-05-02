<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * Tv Show Season
 * @package App\DTO
 * @author Michael A. <michael@logad.net>
 */
final class TvSeason
{
	/**
	 * @var null|TvEpisode[]
	 */
	private ?array $episodes;

    public function __construct(
        public int $id,
        public int $number,
        public string $title,
        public string $overview,
        public float $rating,
        public ?string $imageUrl,
        public string $releaseDate,
        ?TvEpisode ...$episodes,
    )
    {
		$this->episodes = $episodes;
        $this->rating = round($this->rating, 2);
    }

	public function toArray(): array
	{
		return get_object_vars($this);
	}
}