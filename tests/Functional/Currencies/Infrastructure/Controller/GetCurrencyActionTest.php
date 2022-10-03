<?php

declare(strict_types=1);

namespace App\Tests\Functional\Currencies\Infrastructure\Controller;

use App\Currencies\Domain\Entity\Currency;
use App\Tests\Resource\Fixture\CurrencyFixture;
use App\Tests\Resource\Fixture\UserFixture;
use App\Tests\Tools\FixtureTools;
use App\Users\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCurrencyActionTest extends WebTestCase
{
    use FixtureTools;

    public function test_get_currency_action(): void
    {
        $client = static::createClient();

        $executor = $this->getDatabaseTools()->loadFixtures([UserFixture::class, CurrencyFixture::class]);
        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);
        /** @var Currency $currency */
        $currency = $executor->getReferenceRepository()->getReference(CurrencyFixture::REFERENCE);

        $client->request(
            'POST',
            '/api/auth/token/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
            ])
        );
        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));

        // act
        $client->request('GET', '/api/currency/date/'.$currency->getCreatedDate()->format('Y-m-d').'/code/'.$currency->getVchCode().'/RUR');

        // assert
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertNotEmpty($data['data']);
    }
}
