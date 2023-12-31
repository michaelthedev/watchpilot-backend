<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\ValidationException;
use App\Models\User;
use App\Services\Auth\AuthService;

/**
 * Auth Controller
 *
 * Handles requests for /auth/* routes
 *
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class AuthController
{
    public function login(): void
    {
        validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $loginRes = AuthService::login(
            input('username'),
            input('password')
        );
        $status = $loginRes['error'] ? 401 : 200;

        response()
            ->httpCode($status)
            ->json([
                'error' => $loginRes['error'],
                'message' => $loginRes['message'],
                'data' => $loginRes['data'] ?? null
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function register(): void
    {
        validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        User::uniqueOrFail('username', input('username'));

        User::create([
            'username' => input('username'),
            'password' => password_hash(input('password'), PASSWORD_DEFAULT)
        ]);

        response()->json([
            'error' => false,
            'message' => 'Registration Successful'
        ]);
    }
}
