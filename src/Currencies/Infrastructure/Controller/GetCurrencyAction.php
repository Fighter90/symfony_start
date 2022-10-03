<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Controller;

use App\Currencies\Infrastructure\Service\CurrencyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/currency/date/{date}/code/{code1}/{code2}', name: 'currency_calc', requirements: ['date' => "\d{4}-\d{2}-\d{2}", 'code1' => '[A-Za-z]{3}', 'code2' => '[A-Za-z]{3}'], defaults: ['code2' => 'RUB'], methods: ['GET'])]
class GetCurrencyAction
{
    public function __construct(
        private readonly CurrencyService $currencyService
    ) {
    }

    public function __invoke(string $date, string $code1, string $code2): JsonResponse
    {
        try {
            $date1 = new \DateTime($date);
            $curs1 = $this->currencyService->getCursArray($date1, $code1, $code2);
            $date2 = (clone $date1)->modify('-1 day');
            $curs2 = $this->currencyService->getCursArray($date2, $code1, $code2);

            if (empty($curs1)) {
                throw new \Exception('Incorrect input data. Params: '.$date1->format('Y-m-d').', '.$code1.', '.$code2);
            }

            $diff = null;

            if (!empty($curs2)) {
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
}
