<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/currency', methods: ['GET'])]
class GetCurrencyAction
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {


        return new JsonResponse([

        ]);
    }
}
