<?php

declare(strict_types=1);

namespace App\Currencies\Application\DTO;

use App\Currencies\Domain\Entity\Currency;

class CurrencyDTO
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $vchCode = null,
        public readonly ?int $vNom = null,
        public readonly ?float $vCurs = null,
        public readonly ?int $vCode = null,
        public readonly ?\DateTime $createdDate = null
    ) {
    }

    public static function empty(): self
    {
        return new self();
    }

    public static function fromEntity(Currency $currency): self
    {
        return new self(
            $currency->getId(),
            $currency->getVchCode(),
            $currency->getVNom(),
            $currency->getVCurs(),
            $currency->getVCode(),
            $currency->getCreatedDate(),
        );
    }
}
