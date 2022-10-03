<?php

declare(strict_types=1);

namespace App\Currencies\Application\Query\GetCalcCurrency;

use App\Shared\Application\Query\QueryInterface;

class GetCalcCurrencyQuery implements QueryInterface
{
    public function __construct(
        public readonly string $vchCode,
        public readonly string $baseVchCode,
        public readonly \DateTime $createdDate
    ) {
    }
}
