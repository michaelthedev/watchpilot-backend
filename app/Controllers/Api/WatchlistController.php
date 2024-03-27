<?php

declare(strict_types=1);

namespace app\Controllers\Api;

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

    public function get(string $uid): void
    {
        $user = request()->user;

        $watchlist = $user->watchlists()
            ->with('items')
            ->where('uid', $uid)
            ->first();

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

        // confirm name is unique
        $existingWatchlist = $user->watchlists()
            ->where('name', $name)
            ->first();
        if ($existingWatchlist) {
            response()->httpCode(400)->json([
                'error' => true,
                'message' => 'Watchlist name already exists',
            ]);
        }

        $watchlist = $user->watchlists()->create([
            'name' => $name,
            'uid' => uniqid(),
        ]);

        response()->httpCode(201)->json([
            'error' => false,
            'message' => 'Watchlist created successfully',
            'data' => $watchlist->only(['id', 'uid', 'name', 'created_at'])
        ]);
    }

    public function update(string $uid): void
    {
        validate([
            'name' => 'required',
        ]);

        $user = request()->user;
        $name = input('name');

        $watchlist = $user->watchlists()
            ->where('uid', $uid)
            ->first();
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
            'data' => $watchlist
        ]);
    }

    public function destroy(string $uid): void
    {
        $user = request()->user;

        $watchlist = $user->watchlists()
            ->where('uid', $uid)
            ->first();
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

    public function storeItem(string $uid): void
    {
        validate([
            'media_id' => 'required',
            'media_type' => 'required',
            'media_title' => 'required',
        ]);

        $user = request()->user;

        $watchlist = $user->watchlists()
            ->where('uid', $uid)
            ->first();
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
