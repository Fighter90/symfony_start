<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Controller;

use App\Currencies\Application\Query\GetCalcCurrency\GetCalcCurrencyQuery;
use App\Currencies\Infrastructure\DTO\CursDTO;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/currency/date/{date}/code/{code1}/{code2}', name: 'currency_calc', requirements: ['date' => "\d{4}-\d{2}-\d{2}", 'code1' => '[A-Za-z]{3}'], defaults: ['code2' => 'RUR'], methods: ['GET'])]
class GetCurrencyAction
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(string $date, string $code1, string $code2): JsonResponse
    {
        $date1 = new \DateTime($date);
        $query1 = new GetCalcCurrencyQuery(
            $code1,
            $code2,
            $date1
        );

        /**
         * @var CursDTO $vCurs1
         */
        $vCurs1 = $this->queryBus->execute($query1);

        if (!$vCurs1->getVCurs()) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Incorrect input data. No data for this params',
            ]);
        }

        $date2 = (clone $date1)->modify('-1 day');
        $query2 = new GetCalcCurrencyQuery(
            $code1,
            $code2,
            $date2
        );

        /**
         * @var CursDTO $vCurs2
         */
        $vCurs2 = $this->queryBus->execute($query2);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                $date1->format('Y-m-d') => $this->getDataArray($vCurs1),
                $date2->format('Y-m-d') => $this->getDataArray($vCurs2),
                'diff' => [
                    'vNom' => $vCurs1->getVNom(),
                    'vchCode1' => $vCurs1->getVchCode1(),
                    'vchCode2' => $vCurs1->getVchCode2(),
                    'vCurs' => round(abs($vCurs1->getVCurs() - $vCurs2->getVCurs()), 4),
                ],
            ],
        ]);
    }

    /**
     * @param CursDTO $vCurs
     * @return array
     */
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
