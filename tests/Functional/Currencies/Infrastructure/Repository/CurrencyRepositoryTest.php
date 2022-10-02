<?php

namespace App\Tests\Functional\Currencies\Infrastructure\Repository;

use App\Currencies\Domain\Factory\CurrencyFactory;
use App\Currencies\Infrastructure\Repository\CurrencyRepository;
use App\Tests\Resource\Fixture\CurrencyFixture;
use App\Tests\Tools\FakerTools;
use Faker\Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyRepositoryTest extends WebTestCase
{
    use FakerTools;

    private CurrencyRepository $repository;
    private Generator $faker;
    private AbstractDatabaseTool $databaseTool;
    private CurrencyFactory $currencyFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = static::getContainer()->get(CurrencyRepository::class);
        $this->currencyFactory = static::getContainer()->get(CurrencyFactory::class);
        $this->faker = $this->getFaker();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * Курс валюты успешно доабвлен.
     */
    public function test_currency_added_successfully(): void
    {
        $currency = $this->currencyFactory->create(
            $this->faker->currencyCode(),
            $this->faker->randomNumber(),
            $this->faker->randomFloat(),
            $this->faker->randomNumber(),
            new \DateTime($this->faker->date())
        );

        // act
        $this->repository->save($currency);

        // assert
        $existingCurrency = $this->repository->findByVchCodeAndCreatedDate(
            $currency->getVchCode(),
            $currency->getCreatedDate()
        );
        $this->assertEquals($currency->getId(), $existingCurrency->getId());
    }

    public function test_currency_found_successfully(): void
    {
        // arrange
        $executor = $this->databaseTool->loadFixtures([CurrencyFixture::class]);
        $currency = $executor->getReferenceRepository()->getReference(CurrencyFixture::REFERENCE);

        // act
        $existingCurrency = $this->repository->findByVchCodeAndCreatedDate(
            $currency->getVchCode(),
            $currency->getCreatedDate()
        );

        // assert
        $this->assertEquals($currency->getId(), $existingCurrency->getId());
    }
}
