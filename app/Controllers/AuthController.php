<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\Auth\AuthService;

/**
 * Auth Controller
 *
 * Handles requests for /auth/* routes
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
final class AuthController
{
    public function login(): void
    {
        validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $loginRes = AuthService::login(
            input('email'), input('password')
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

    public function register(): void
    {
        response()->json([
            'error' => false,
            'message' => 'Registration Successful',
            'data' => [
                'token' => 'token'
            ]
        ]);
    }
}