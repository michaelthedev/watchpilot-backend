<?php

namespace App\Models;

use App\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User Model
 *
 * @package App\Models
 * @author Michael Arawole <michael@logad.net>
 */
final class User extends Model
{

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = ['username', 'password'];

    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    /**
     * @throws ValidationException
     */
    public static function uniqueOrFail(string $search, string $value): void
    {
        if (self::where($search, $value)->exists()) {
            $search = ucwords(str_replace('_', ' ', $search));
            throw new ValidationException("$search already exists");
        }
    }

    public static function findByUsername(string $username): ?User
    {
        return self::where('username', $username)->first();
    }
}