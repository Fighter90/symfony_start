<?php

declare(strict_types=1);

namespace App\Currencies\Application\Command\UpdateCurrency;

use App\Currencies\Domain\Entity\Currency;
use App\Shared\Application\Command\CommandInterface;

class UpdateCurrencyCommand implements CommandInterface
{
    public function __construct(
        public readonly int $id,
        public readonly int $vNom,
        public readonly float $vCurs
    ) {
    }
}
