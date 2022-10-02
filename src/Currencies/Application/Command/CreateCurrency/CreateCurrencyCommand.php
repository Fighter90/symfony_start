<?php

declare(strict_types=1);

namespace App\Currencies\Application\Command\CreateCurrency;

use App\Shared\Application\Command\CommandInterface;

class CreateCurrencyCommand implements CommandInterface
{
    public function __construct(
        public readonly string $vchCode,
        public readonly int $vNom,
        public readonly float $vCurs,
        public readonly int $vCode,
        public readonly \DateTime $createdDate
    ) {
    }
}
