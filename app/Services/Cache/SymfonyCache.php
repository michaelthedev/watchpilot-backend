<?php

declare(strict_types=1);

namespace App\Services\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\Cache\ItemInterface;

final class SymfonyCache implements CacheInterface
{
    private AdapterInterface $cache;

    public function __construct(string $driver = 'file', array $options = [])
    {
		if ($driver == 'file') {
			$this->cache = new FilesystemAdapter(
				directory: $options['path'] ?? sys_get_temp_dir()
			);
		} elseif ($driver == 'redis') {
			$client = RedisAdapter::createConnection(
				'redis://localhost'
			);
			$this->cache = new RedisAdapter($client);
		}
    }

    public function get(string $key): mixed
    {
        $cachedItem = $this->cache->getItem($key);
        if ($cachedItem->isHit()) {
            return $cachedItem->get();
        }

        return null;
    }

    public function store(string $key, mixed $value, int $expiry = 0): void
    {
        $cachedItem = $this->cache->getItem($key);
        $cachedItem->set($value);
        $cachedItem->expiresAfter($expiry);
        $this->cache->save($cachedItem);
    }

	public function getOrSet(string $key, mixed $value, int $expiry): mixed
	{
		$expiry = $this->secondsToDateTime($expiry);
		return $this->cache->get($key, function(ItemInterface $item) use($expiry, $value) {
			$item->expiresAt($expiry);

			if (is_callable($value)) {
				$value = $value();
			}

			return [
				'value' => $value,
				'expiry' => $expiry->format('c')
			];
		})['value'] ?? null;
	}

	private function secondsToDateTime(int $expiry): \DateTime
	{
		$dateTime = new \DateTime();
		$dateTime->modify("+$expiry seconds");

		return $dateTime;
	}

    public function delete(string $key): bool
    {
        return $this->cache->deleteItem($key);
    }

	public function deleteAll(): bool
	{
		return $this->cache->clear();
	}

	public function prune(): bool
	{
		return $this->cache->prune();
	}
}