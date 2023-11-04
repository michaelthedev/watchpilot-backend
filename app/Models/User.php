<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * User Model
 *
 * @package App\Models
 * @author Michael Arawole <michael@logad.net>
 */
final class User extends Model
{

    public static function findByUsername(string $username): ?User
    {
        return self::where('username', $username)->first();
    }
}