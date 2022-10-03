<?php

namespace App\Tests\Functional\Currencies\Application\Query\GetCalcCurrency;

use App\Currencies\Application\DTO\CursDTO;
use App\Currencies\Application\Query\GetCalcCurrency\GetCalcCurrencyQuery;
use App\Currencies\Domain\Entity\Currency;
use App\Shared\Application\Query\QueryBusInterface;
use App\Tests\Resource\Fixture\CurrencyFixture;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCalcCurrencyHandlerTest extends WebTestCase
{
    private const DEFAULT_VCH_CODE = 'RUB';
    private QueryBusInterface $queryBus;
    private AbstractDatabaseTool $databaseTool;

    public function setUp(): void
    {
        parent::setUp();
        $this->queryBus = $this::getContainer()->get(QueryBusInterface::class);
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_currency_success_calc_when_command_executed(): void
    {
        // arrange
        $referenceRepository = $this->databaseTool->loadFixtures([CurrencyFixture::class])->getReferenceRepository();
        /** @var Currency $currency */
        $currency = $referenceRepository->getReference(CurrencyFixture::REFERENCE);

        $query = new GetCalcCurrencyQuery(
            $currency->getVchCode(),
            self::DEFAULT_VCH_CODE,
            $currency->getCreatedDate()
        );

        /**
         * @var CursDTO $vCurs
         */
        $vCurs = $this->queryBus->execute($query);

        // assert
        $this->assertInstanceOf(CursDTO::class, $vCurs);
        $this->assertEquals($currency->getVCurs(), $vCurs->getVCurs());
    }

    public function test_currency_error_calc_when_command_executed(): void
    {
        // arrange
        $referenceRepository = $this->databaseTool->loadFixtures([CurrencyFixture::class])->getReferenceRepository();
        /** @var Currency $currency */
        $currency = $referenceRepository->getReference(CurrencyFixture::REFERENCE);

        $errorDate = (clone $currency->getCreatedDate())->modify("+1 day");
        $query = new GetCalcCurrencyQuery(
            $currency->getVchCode(),
            self::DEFAULT_VCH_CODE,
            $errorDate
        );

        /**
         * @var CursDTO $vCurs
         */
        $vCurs = $this->queryBus->execute($query);

        // assert
        $this->assertInstanceOf(CursDTO::class, $vCurs);
        $this->assertEmpty($vCurs->getVCurs());
    }
}
