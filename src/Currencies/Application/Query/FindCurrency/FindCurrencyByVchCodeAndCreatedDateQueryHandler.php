<?php

declare(strict_types=1);

namespace App\Currencies\Application\Query\FindCurrency;

use App\Currencies\Application\DTO\CurrencyDTO;
use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

class FindCurrencyByVchCodeAndCreatedDateQueryHandler implements QueryHandlerInterface
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository)
    {
    }

    public function __invoke(FindCurrencyByVchCodeAndCreatedDateQuery $query): CurrencyDTO
    {
        $currency = $this->currencyRepository->findByVchCodeAndCreatedDate(
            $query->vchCode,
            $query->createdDate
        );

        if (!$currency) {
            return CurrencyDTO::empty();
        }

        return CurrencyDTO::fromEntity($currency);
    }
}
