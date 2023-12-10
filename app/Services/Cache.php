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

	public static function delete(string $key): void
	{
		self::$cache->delete($key);
	}

	public static function getOrSet(
		string $key,
		Closure $param,
		int $expiry = 3600
	): mixed
	{
		if (self::exists($key)) {
			return self::get($key);
		}

		$value = $param();
		self::store($key, $value, $expiry);

		return $value;
	}

	public static function deleteAll(): void
	{
		self::$cache->deleteAll();
	}
}