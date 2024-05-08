<?php

declare(strict_types=1);

namespace App\Services\Cache;

use DateTimeInterface;

interface CacheInterface
{
	public function get(string $key): mixed;

	public function store(string $key, mixed $value, int $expiry): void;

	/**
	 * @param string $key
	 * @param mixed $value value to store if not found
	 * @param null|DateTimeInterface $expiry
	 * @return mixed cache value if found
	 */
	public function getOrSet(string $key, mixed $value, ?DateTimeInterface $expiry): mixed;

	public function delete(string $key): bool;

	public function deleteAll(): bool;

	public function prune(): bool;
}