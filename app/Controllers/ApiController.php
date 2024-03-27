<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * API Controller
 *
 * Handles requests for /api/* routes
 * @package App\Controllers
 * @author Michael Arawole <michael@logad.net>
 */
class ApiController
{
    public function pong(): void
    {
        $this->json([
			'error' => false,
			'message' => "I'm alive!",
			'data' => [
				'app_name' => config('app.name')
			]
		]);
    }

	final protected function json(array $data, int $statusCode = 200): void
	{
		response()
			->httpCode($statusCode)
			->json($data);
	}

	final protected function error(string $message = null, int $statusCode = 400): void
	{
		response()
			->httpCode($statusCode)
			->json([
				'error' => true,
				'message' => $message ?? 'An error occurred'
			]);
	}

	final protected function success(string $message = null, ?array $data = null, int $statusCode = 200): void
	{
		response()
			->httpCode($statusCode)
			->json([
				'error' => false,
				'message' => $message,
				'data' => $data
			]);
	}
}