<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Watchlist Model
 *
 * @package App\Models
 * @author Michael Arawole <michael@logad.net>
 */
final class Watchlist extends Model
{
    protected $fillable = ['name', 'uid'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WatchlistItem::class);
    }
}