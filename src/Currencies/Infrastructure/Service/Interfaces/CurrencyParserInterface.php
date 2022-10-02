<?php

declare(strict_types=1);

namespace App\Currencies\Infrastructure\Service\Interfaces;

use App\Currencies\Infrastructure\DTO\CurrencyDTO;

interface CurrencyParserInterface
{
    /**
     * @return CurrencyDTO[]
     */
    public function parse(?string $date): array;
}
