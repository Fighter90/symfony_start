<?php

namespace App\Tests\Functional\Currencies\Application\Query\FindCurrency;

use App\Currencies\Application\DTO\CurrencyDTO;
use App\Currencies\Application\Query\FindCurrency\FindCurrencyByVchCodeAndCreatedDateQuery;
use App\Currencies\Domain\Entity\Currency;
use App\Shared\Application\Query\QueryBusInterface;
use App\Tests\Resource\Fixture\CurrencyFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindCurrencyByVchCodeAndCreatedDateQueryHandlerTest extends WebTestCase
{
    private QueryBusInterface $queryBus;
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = $this::getContainer()->get(QueryBusInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_currency_created_when_command_executed(): void
    {
        // arrange
        $referenceRepository = $this->databaseTool->loadFixtures([CurrencyFixture::class])->getReferenceRepository();
        /** @var Currency $currency */
        $currency = $referenceRepository->getReference(CurrencyFixture::REFERENCE);
        $query = new FindCurrencyByVchCodeAndCreatedDateQuery(
            $currency->getVchCode(),
            $currency->getCreatedDate()
        );

        // act
        $currencyDTO = $this->queryBus->execute($query);

        // assert
        $this->assertInstanceOf(CurrencyDTO::class, $currencyDTO);
    }
}
