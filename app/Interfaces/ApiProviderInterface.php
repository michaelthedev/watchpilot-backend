<?php

namespace App\Interfaces;

use App\DTO\MovieDetail;

interface ApiProviderInterface
{
    public function getTrendingMoviesAndShows(): array;

    public function getMovieDetails(int $id): MovieDetail;
}