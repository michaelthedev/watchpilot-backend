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

    public function update(int $watchlist_id): void
    {
        validate([
            'name' => 'required',
        ]);

        $user = request()->user;
        $name = input('name');

        $watchlist = $user->watchlists()->find($watchlist_id);
        if (!$watchlist) {
            response()->httpCode(400)->json([
                'error' => true,
                'message' => 'Watchlist not found',
            ]);
        }

        $watchlist->update([
            'name' => $name
        ]);

        response()->httpCode(200)->json([
            'error' => false,
            'message' => 'Watchlist updated successfully',
            'data' => $watchlist->only(['id', 'name', 'updated_at'])
        ]);
    }
        ]);
    }
}