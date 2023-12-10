<?php

declare(strict_types=1);

namespace App\Services\Cache;

interface CacheInterface
{
	public function get(string $key): mixed;

	public function store(string $key, mixed $value, int $expiry): void;

	public function delete(string $key): void;
}