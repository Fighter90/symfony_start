<?php

declare(strict_types=1);

namespace App\Currencies\Application\DTO;

use App\Currencies\Domain\Entity\Currency;

class CurrencyDTO
{
    public function __construct(
        public readonly string $vchCode,
        public readonly int $vNom,
        public readonly float $vCurs,
        public readonly int $vCode,
        public readonly \DateTime $createdDate
    ) {
    }

    public static function fromEntity(Currency $currency): self
    {
        return new self(
            $currency->getVchCode(),
            $currency->getVNom(),
            $currency->getVCurs(),
            $currency->getVCode(),
            $currency->getCreatedDate(),
        );
    }
}
