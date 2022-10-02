<?php

declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\Currencies\Domain\Factory\CurrencyFactory;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixture extends Fixture
{
    use FakerTools;

    public const REFERENCE = 'currency';

    public function __construct(private readonly CurrencyFactory $currencyFactory)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $currency = $this->currencyFactory->create(
                $this->getFaker()->currencyCode(),
                $this->getFaker()->randomNumber(),
                $this->getFaker()->randomFloat(),
                $this->getFaker()->randomNumber(),
                new \DateTime($this->getFaker()->date('Y-m-d'))
        );

        $manager->persist($currency);
        $manager->flush();

        $this->addReference(self::REFERENCE, $currency);
    }
}
