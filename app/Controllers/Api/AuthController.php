<?php

declare(strict_types=1);

namespace App\Controllers\Api;

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
final class AuthController extends ApiController
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

		$this->json([
			'error' => $loginRes['error'],
			'message' => $loginRes['message'],
			'data' => $loginRes['data'] ?? null
		], $loginRes['error'] ? 401 : 200);
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

		$this->success('Registration Successful');
    }

	public function validateToken(): void
	{

	}
}
