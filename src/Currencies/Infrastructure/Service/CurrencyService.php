<?php

namespace App\Currencies\Infrastructure\Service;

use App\Currencies\Application\DTO\CursDTO;
use App\Currencies\Application\Query\GetCalcCurrency\GetCalcCurrencyQuery;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Cache\CacheInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CurrencyService
{
    private const CACHE_PREFIX = 'curs_';

    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly AdapterInterface $cachePool,
        private readonly CacheInterface $cacheUtil
    ) {
    }

    public function getCursArray(\DateTime $date, string $code1, string $code2): array
    {
        $key = self::CACHE_PREFIX.$date->format('Y-m-d').'_'.$code1.'_'.$code2;
        $data = $this->cacheUtil->getItem($this->cachePool, $key);

        if (!empty($data)) {
            return $data;
        }

        $query = new GetCalcCurrencyQuery(
            $code1,
            $code2,
            $date
        );

        /**
         * @var CursDTO $vCurs
         */
        $vCurs = $this->queryBus->execute($query);

        if (!$vCurs->getVCurs()) {
            return [];
        }

        $data = $this->getDataArray($vCurs);
        $this->cacheUtil->saveItem($this->cachePool, $key, $data);

        return $data;
    }

    public function getDataArray(CursDTO $vCurs): array
    {
        return [
            'vchCode1' => $vCurs->getVchCode1(),
            'vchCode2' => $vCurs->getVchCode2(),
            'vNom' => $vCurs->getVNom(),
            'vCurs' => $vCurs->getVCurs(),
        ];
    }
}
