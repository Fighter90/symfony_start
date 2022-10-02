<?php

declare(strict_types=1);

namespace App\Currencies\Application\Command\UpdateCurrency;

use App\Currencies\Domain\Factory\CurrencyFactory;
use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;

class UpdateCurrencyCommandHandler implements CommandHandlerInterface
{
    public function __construct(private readonly CurrencyRepositoryInterface $currencyRepository)
    {
    }

    /**
     * @return int Currency Id
     */
    public function __invoke(UpdateCurrencyCommand $updateCurrencyCommand): int
    {
        $currency = $this->currencyRepository->findById($updateCurrencyCommand->id);
        $currency->setVCurs($updateCurrencyCommand->vCurs);
        $currency->setVNom($updateCurrencyCommand->vNom);
        $this->currencyRepository->save($currency);

        return $currency->getId();
    }
}
