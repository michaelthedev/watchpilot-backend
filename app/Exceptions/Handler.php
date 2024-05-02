<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Services\Log;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

class Handler
{
	/*
	 * Don't log exceptions but render
	 */
	private array $dontLog = [
		ValidationException::class,
		ApplicationException::class
	];

	final public function report(\Throwable $e): void
	{
		$this->log($e);

		if ($e instanceof NotFoundHttpException) {
			if (request()->isFormatAccepted('application/json')) {
				response()->httpCode(404)->json([
					'error' => true,
					'message' => $e->getMessage()
				]);
			}

			http_response_code(404);
			echo '404 - Page not found';
			return;
		}


		if (
			$e instanceof ValidationException
			|| $e instanceof ApplicationException
		) {
			response()->httpCode(400)->json([
				'error' => true,
				'message' => $e->getMessage(),
			]);
		}

		$this->fallback($e);
	}

	private function fallback(\Throwable $e): void
	{
		response()->httpCode(500)->json([
			'error' => true,
			'message' => 'Internal Server Error',
		]);
	}

	private function log(\Throwable $e): void
	{
		if (!in_array(get_class($e), $this->dontLog)) {
			Log::critical('An exception occurred', [
				'e' => $e
			]);
		}
	}
}