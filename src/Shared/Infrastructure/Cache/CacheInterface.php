<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;

interface CacheInterface
{
    public function saveItem(AdapterInterface $cachePool, string $key, array $value): bool;

    public function getItem(AdapterInterface $cachePool, string $key): ?array;

    public function deleteItem(AdapterInterface $cachePool, string $key): bool;

    public function deleteAll(AdapterInterface $cachePool): bool;
}