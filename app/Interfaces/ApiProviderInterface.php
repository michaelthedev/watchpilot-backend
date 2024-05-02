<?php

namespace App\Interfaces;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;

interface ApiProviderInterface
{
    public function getTrendingMoviesAndShows(): array;

    public function getMovieDetails(int $id): MovieDetail;

    public function getTvDetails(int $id): TvDetail;

	public function getWatchProviders(string $type, int $id): array;

    /**
     * @param string $query What to search for
     * @param string $type Search a tv show, movie or all
     * @return array
     */
    public function search(string $query, string $type): array;
}