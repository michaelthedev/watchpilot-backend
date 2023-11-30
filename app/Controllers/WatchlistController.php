<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Watchlist Controller
 *
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class WatchlistController
{
    public function index(): void
    {
        $user = request()->user;

        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $user->watchlists
        ]);
    }

    public function store(): void
    {
        validate([
            'name' => 'required',
        ]);

        $user = request()->user;
        $name = input('name');

        $watchlist = $user->watchlists()->create([
            'name' => $name
        ]);

        response()->httpCode(201)->json([
            'error' => false,
            'message' => 'Watchlist created successfully',
            'data' => $watchlist->only(['id', 'name', 'created_at'])
        ]);
    }

        ]);
    }
}