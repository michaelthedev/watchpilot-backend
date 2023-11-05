<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Watchlist Item Model
 *
 * @package App\Models
 * @author Michael Arawole <michael@logad.net>
 */
final class WatchlistItem extends Model
{
    protected $fillable = [];

    public function watchlist(): BelongsTo
    {
        return $this->belongsTo(Watchlist::class);
    }
}