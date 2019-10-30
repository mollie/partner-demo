<?php declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Mollie\OAuth2\Client\Provider\Mollie;
use Tests\TestCase;

class OAuthClientServiceProviderTest extends TestCase
{
    const MOLLIE_CUSTOM_API_URL = 'http://api.mollie.nl';
    const MOLLIE_CUSTOM_WEB_URL = 'http://www.mollie.nl';

    public function testEnsureMollieClientIsRegisteredIntoContainer(): void
    {
        $client = $this->app->get(Mollie::class);

        $this->assertInstanceOf(Mollie::class, $client);
    }

    public function testWhenEnvHasDifferentMollieApiUrlThenSetOnClient(): void
    {
        $_ENV['MOLLIE_API_URL'] = self::MOLLIE_CUSTOM_API_URL;
        $_ENV['MOLLIE_WEB_URL'] = self::MOLLIE_CUSTOM_WEB_URL;

        $client = $this->app->get(Mollie::class);

        $expected = new Mollie([
            'clientId' => $_ENV['MOLLIE_CLIENT_ID'],
            'clientSecret' => $_ENV['MOLLIE_CLIENT_SECRET'],
            'redirectUri' => $_ENV['APP_URL'].'/settings/payment/oauth/return',
            'verify' => 'false',
        ]);

        $expected->setMollieWebUrl(self::MOLLIE_CUSTOM_WEB_URL);
        $expected->setMollieApiUrl(self::MOLLIE_CUSTOM_API_URL);
        $this->assertEquals($expected, $client);
    }
}
