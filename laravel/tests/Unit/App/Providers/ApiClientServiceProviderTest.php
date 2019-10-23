<?php declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Mollie\Api\MollieApiClient;
use Tests\TestCase;

class ApiClientServiceProviderTest extends TestCase
{
    const MOLLIE_CUSTOM_URL = 'http://api.mollie.nl';

    public function testEnsureMollieClientIsRegisteredIntoContainer(): void
    {
        $client = $this->app->get(MollieApiClient::class);

        $this->assertEquals(new MollieApiClient(), $client);
    }

    public function testWhenEnvHasDifferentMollieApiUrlThenSetOnClient(): void
    {
        $_ENV['MOLLIE_API_URL'] = self::MOLLIE_CUSTOM_URL;

        $client = $this->app->get(MollieApiClient::class);

        $expected = new MollieApiClient();
        $expected->setApiEndpoint(self::MOLLIE_CUSTOM_URL);
        $this->assertEquals($expected, $client);
    }
}
