<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * User Controller
 *
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class UserController
{
    public function index(): void
    {
        $user = request()->user;

        response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $user
        ]);
    }
}