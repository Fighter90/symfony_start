<?php

declare(strict_types=1);

namespace App\Currencies\Application\Command\CreateCurrency;

use App\Currencies\Domain\Factory\CurrencyFactory;
use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

class CreateCurrencyCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository, private readonly CurrencyFactory $currencyFactory)
    {
    }

    /**
     * @return int Currency Id
     */
    public function __invoke(CreateCurrencyCommand $createCurrencyCommand): int
    {
        $currency = $this->currencyFactory->create(
                $createCurrencyCommand->vchCode,
                $createCurrencyCommand->vNom,
                $createCurrencyCommand->vCurs,
                $createCurrencyCommand->vCode,
                $createCurrencyCommand->createdDate
        );
        $this->currencyRepository->save($currency);

        return $currency->getId();
    }
}
