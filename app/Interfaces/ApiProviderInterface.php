<?php

namespace App\Interfaces;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;

interface ApiProviderInterface
{
    public function getTrendingMoviesAndShows(): array;

    public function getMovieDetails(int $id): MovieDetail;

    public function getTvDetails(int $id): TvDetail;
}