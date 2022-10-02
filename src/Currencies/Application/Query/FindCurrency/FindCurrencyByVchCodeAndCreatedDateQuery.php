<?php

declare(strict_types=1);

namespace App\Currencies\Application\Query\FindCurrency;

use App\Shared\Application\Query\QueryInterface;

class FindCurrencyByVchCodeAndCreatedDateQuery implements QueryInterface
{
    public function __construct(public readonly string $vchCode, public readonly \DateTime $createdDate)
    {
    }
}
