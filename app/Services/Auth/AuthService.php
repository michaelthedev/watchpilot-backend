<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

/**
 * Auth Service
 *
 * @package App\Services\Auth
 * @author Michael Arawole <michael@logad.net>
 */
final class AuthService
{
    public static function login(string $username, string $password): array
    {
        $response['error'] = true;
        $response['message'] = 'Login Failed. Please check your details';

        $user = User::findByUsername($username);
        if (!$user) return $response;

        // Verify password
        if (password_verify($password, $user->password)) {
            $token = self::generateToken();
            $user->remember_token = $token;
            $user->save();

            // Generate JWT
            $jwt = JwtService::encode([
                'token' => $user->remember_token
            ]);

            $response['error'] = false;
            $response['message'] = 'Login Successful';
            $response['data'] = $jwt;
        }

        return $response;
    }

    private static function generateToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function authenticate(string $token): ?User
    {
        $verify = JwtService::verify($token);
        if (empty($verify['token'])) return null;

        return User::where('remember_token', $verify['token'])->first();
    }
}
