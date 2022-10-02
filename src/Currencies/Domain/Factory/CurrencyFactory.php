<?php

declare(strict_types=1);

namespace App\Currencies\Domain\Factory;

use App\Currencies\Domain\Entity\Currency;

class CurrencyFactory
{
    public function create(string $vchCode, int $vNom, float $vCurs, int $vCode, \DateTime $createdDate): Currency
    {
        return new Currency(
            $vchCode,
            $vNom,
            $vCurs,
            $vCode,
            $createdDate,
        );
    }
}
