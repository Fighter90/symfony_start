<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Controller;

use App\Currencies\Application\Query\GetCalcCurrency\GetCalcCurrencyQuery;
use App\Currencies\Infrastructure\DTO\CursDTO;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Cache\CacheInterface;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/currency/date/{date}/code/{code1}/{code2}', name: 'currency_calc', requirements: ['date' => "\d{4}-\d{2}-\d{2}", 'code1' => '[A-Za-z]{3}'], defaults: ['code2' => 'RUB'], methods: ['GET'])]
class GetCurrencyAction
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly MemcachedAdapter $cachePool,
        private readonly CacheInterface $cacheUtil
    ) {
    }

    public function __invoke(string $date, string $code1, string $code2): JsonResponse
    {
        try {
            $date1 = new \DateTime($date);
            $curs1 = $this->getCursArray($date1, $code1, $code2);
            $date2 = (clone $date1)->modify('-1 day');
            $curs2 = $this->getCursArray($date2, $code1, $code2);

            $diff = null;

            if (!empty($curs1) && !empty($curs2)) {
                $diff = round(abs($curs1['vCurs'] - $curs2['vCurs']), 4);
            }

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    $date1->format('Y-m-d') => $curs1,
                    $date2->format('Y-m-d') => $curs2,
                    'diff' => [
                        'vNom' => $curs1['vNom'] ?? null,
                        'vchCode1' => $curs1['vchCode1'] ?? null,
                        'vchCode2' => $curs1['vchCode2'] ?? null,
                        'vCurs' => $diff,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function getCursArray(\DateTime $date, string $code1, string $code2): array
    {
        $key = 'curs_'.$date->format('Y-m-d').'_'.$code1.'_'.$code2;
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
         * @var CursDTO $vCurs1
         */
        $vCurs = $this->queryBus->execute($query);

        if (!$vCurs->getVCurs()) {
            return [];
        }

        $data = $this->getDataArray($vCurs);
        $this->cacheUtil->saveItem($this->cachePool, $key, $data);

        return $data;
    }

    private function getDataArray(CursDTO $vCurs): array
    {
        return [
            'vchCode1' => $vCurs->getVchCode1(),
            'vchCode2' => $vCurs->getVchCode2(),
            'vNom' => $vCurs->getVNom(),
            'vCurs' => $vCurs->getVCurs(),
        ];
    }
}
