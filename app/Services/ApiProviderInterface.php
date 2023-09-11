<?php

namespace App\Services;

interface ApiProviderInterface
{
    public function getTrendingMoviesAndShows(): array;
}