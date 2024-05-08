<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Cache\CacheInterface;
use App\Services\Cache\SymfonyCache;
use Closure;

final class Cache
{
	private static CacheInterface $cache;

	public static function boot(): void
	{
		$driver = config('cache.driver');
		$options = config("cache.stores.$driver") ?? [];

		self::$cache = new SymfonyCache($driver, $options);
	}

	public static function exists(string $key): bool
	{
		return self::$cache->get($key) !== null;
	}

	public static function get(string $key): mixed
	{
		return self::$cache->get($key);
	}

	public static function store(string $key, mixed $value, int $expiry = 0): void
	{
		self::$cache->store($key, $value, $expiry);
	}

	public static function delete(string $key): bool
	{
		return self::$cache->delete($key);
	}

	public static function getOrSet(
		string $key,
		mixed $value,
		?int $expiry = null
	): mixed
	{
		//todo: allow datetime
		return self::$cache->getOrSet($key, $value, $expiry);
	}

	public static function deleteAll(): void
	{
		self::$cache->deleteAll();
	}

	public static function prune(): bool
	{
		return self::$cache->prune();
	}
}