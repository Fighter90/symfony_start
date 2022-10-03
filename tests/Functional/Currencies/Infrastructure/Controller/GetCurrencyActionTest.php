<?php

declare(strict_types=1);

namespace App\Tests\Functional\Currencies\Infrastructure\Controller;

use App\Currencies\Domain\Entity\Currency;
use App\Tests\Resource\Fixture\CurrencyFixture;
use App\Tests\Resource\Fixture\UserFixture;
use App\Tests\Tools\FixtureTools;
use App\Users\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCurrencyActionTest extends WebTestCase
{
    use FixtureTools;

    private Currency $currency;

    private KernelBrowser $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $executor = $this->getDatabaseTools()->loadFixtures([UserFixture::class, CurrencyFixture::class]);
        /** @var User $user */
        $user = $executor->getReferenceRepository()->getReference(UserFixture::REFERENCE);
        /* @var Currency $currency */
        $this->currency = $executor->getReferenceRepository()->getReference(CurrencyFixture::REFERENCE);

        $this->client->request(
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
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $data['token']));
    }

    public function test_get_currency_success_test_action(): void
    {
        // act
        $this->client->request('GET', '/api/currency/date/'.$this->currency->getCreatedDate()->format('Y-m-d').'/code/'.$this->currency->getVchCode().'/RUB');

        // assert
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertNotEmpty($data['data']);
    }
}
