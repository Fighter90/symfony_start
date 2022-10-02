<?php

declare(strict_types=1);

namespace App\Currencies\Domain\Repository;

use App\Currencies\Domain\Entity\Currency;

interface CurrencyRepositoryInterface
{
    public function save(Currency $currency): void;

    public function findByVchCodeAndCreatedDate(string $vchCode, \DateTime $createdDate): ?Currency;

    public function findById(int $id): ?Currency;
}
