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

    public function get(int $watchlist_id): void
    {
        $user = request()->user;

        $watchlist = $user->watchlists()->with('items')->find($watchlist_id);
        if (!$watchlist) {
            response()->httpCode(400)->json([
                'error' => true,
                'message' => 'Watchlist not found',
            ]);
        }

        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $watchlist
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

    public function destroy(int $watchlist_id): void
    {
        $user = request()->user;

        $watchlist = $user->watchlists()->find($watchlist_id);
        if (!$watchlist) {
            response()->httpCode(400)->json([
                'error' => true,
                'message' => 'Watchlist not found',
            ]);
        }

        $watchlist->items()->delete();
        $watchlist->delete();

        response()->httpCode(200)->json([
            'error' => false,
            'message' => 'Watchlist deleted successfully',
        ]);
    }

    public function storeItem(int $watchlist_id): void
    {
        validate([
            'media_id' => 'required',
            'media_type' => 'required',
            'media_title' => 'required',
        ]);

        $user = request()->user;

        $watchlist = $user->watchlists()->find($watchlist_id);
        if (!$watchlist) {
            response()->httpCode(400)->json([
                'error' => true,
                'message' => 'Watchlist not found',
            ]);
        }

        $watchlist->items()->create([
            'title' => input('media_title'),
            'type' => input('media_type'),
            'item_id' => input('media_id'),
        ]);

        response()->httpCode(200)->json([
            'error' => false,
            'message' => 'Item added to watchlist successfully',
        ]);
    }
}