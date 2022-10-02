<?php

namespace App\Tests\Functional\Currencies\Application\Command\UpdateCurrency;

use App\Currencies\Application\Command\CreateCurrency\CreateCurrencyCommand;
use App\Currencies\Application\Command\UpdateCurrency\UpdateCurrencyCommand;
use App\Currencies\Domain\Repository\CurrencyRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use App\Tests\Tools\FakerTools;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateCurrencyCommandHandlerTest extends WebTestCase
{
    use FakerTools;

    private CommandBusInterface $commandBus;
    private CurrencyRepositoryInterface $currencyRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this::getContainer()->get(CommandBusInterface::class);
        $this->currencyRepository = $this::getContainer()->get(CurrencyRepositoryInterface::class);
    }

    public function test_currency_updated_successfully(): void
    {
        $command = new CreateCurrencyCommand(
            $this->getFaker()->currencyCode(),
            $this->getFaker()->randomNumber(),
            $this->getFaker()->randomFloat(),
            $this->getFaker()->randomNumber(),
            new \DateTime($this->getFaker()->date('Y-m-d'))
        );

        // act
        $currencyId = $this->commandBus->execute($command);

        // assert
        $currency = $this->currencyRepository->findById($currencyId);

        $vNom = $this->getFaker()->randomNumber();
        $vCurs = $this->getFaker()->randomFloat();

        $command = new UpdateCurrencyCommand(
            $currency->getId(),
            $vNom,
            $vCurs
        );

        // act
        $currencyId = $this->commandBus->execute($command);

        // assert
        $currency = $this->currencyRepository->findById($currencyId);
        $this->assertEquals($vNom, $currency->getVNom());
        $this->assertEquals($vCurs, $currency->getVCurs());
    }
}
