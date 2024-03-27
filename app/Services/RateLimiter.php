<?php

declare(strict_types=1);

namespace App\Services;

class RateLimiter
{
	/**
	 * @param string $identifier
	 * @param int $maxAttempts
	 * @param int $limit Time in minutes
	 * @return void
	 */
	public static function throttle(string $identifier, int $maxAttempts, int $limit): void
	{
		$key = 'rateLimiter_'.$identifier;
		$limit = $limit * 60;

		$requests = (int) Cache::getOrSet($key, 0, $limit);

		if ($requests < $maxAttempts) {
			Cache::increment($key);
		} else {
			response()
				->httpCode(429)
				->json([
					'error' => true,
					'message' => 'Too many requests. Please try again later.'
				]);
		}
	}
}