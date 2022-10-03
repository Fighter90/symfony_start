<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class MemcachedUtil implements CacheInterface
{
    public function saveItem(AdapterInterface $cachePool, string $key, array $value): bool
    {
        $item = $this->fetch($cachePool, $key);
        $item->set($value);

        return $cachePool->save($item);
    }

    public function getItem(AdapterInterface $cachePool, string $key): ?array
    {
        $result = null;

        $item = $this->fetch($cachePool, $key);
        if ($item->isHit()) {
            $result = $item->get();
        }

        return $result;
    }

    public function deleteItem(AdapterInterface $cachePool, string $key): bool
    {
        return $cachePool->deleteItem($key);
    }

    public function deleteAll(AdapterInterface $cachePool): bool
    {
        return $cachePool->clear();
    }

    private function fetch(AdapterInterface $cachePool, string $key): CacheItemInterface
    {
        try {
            return $cachePool->getItem($key);
        } catch (InvalidArgumentException $e) {
            throw new CacheException($e->getMessage());
        }
    }
}