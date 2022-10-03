<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use App\Tests\Resource\Fixture\UserFixture;
use App\Tests\Resource\Fixture\CurrencyFixture;
use App\Users\Domain\Entity\User;
use App\Currencies\Domain\Entity\Currency;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

trait FixtureTools
{
    public function getDatabaseTools(): AbstractDatabaseTool
    {
        return static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function loadUserFixture(): User
    {
        $executor = $this->getDatabaseTools()->loadFixtures([UserFixture::class]);
        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);

        return $user;
    }

    public function loadCurrencyFixture(): Currency
    {
        $executor = $this->getDatabaseTools()->loadFixtures([CurrencyFixture::class]);
        /** @var Currency $currency */
        $currency = $executor->getReferenceRepository()->getReference(CurrencyFixture::REFERENCE);

        return $currency;
    }
}
