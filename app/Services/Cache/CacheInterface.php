<?php

declare(strict_types=1);

namespace App\Services\Cache;

interface CacheInterface
{
	public function get(string $key): mixed;

	public function store(string $key, mixed $value, int $expiry): void;

	public function getOrSet(string $key, mixed $value, int $expiry): mixed;

	public function delete(string $key): bool;

	public function deleteAll(): bool;

	public function prune(): bool;
}