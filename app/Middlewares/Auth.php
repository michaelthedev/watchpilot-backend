<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Services\Auth\AuthService;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

/**
 * Auth Middleware
 *
 * @package App\Middlewares
 * @author Michael Arawole <michael@logad.net>
 */
final class Auth implements IMiddleware
{
    public function handle(Request $request): void
    {
        $token = $this->getBearerToken();
        if (empty($token)) {
            response()
                ->httpCode(401)
                ->json([
                    'error' => true,
                    'message' => 'Unauthorized'
                ]);
        }

        // Authenticated user, will be available using request()->user
        $request->user = AuthService::authenticate($token);
        if ($request->user === null) {
            response()
                ->httpCode(401)
                ->json([
                    'error' => true,
                    'message' => 'Unauthorized'
                ]);
        }
    }

    private function getBearerToken(): ?string
    {
        $authHeader = getallheaders()['Authorization'] ?? null;
        if (empty($authHeader)) return null;

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }
}