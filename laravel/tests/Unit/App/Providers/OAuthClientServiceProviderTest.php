<?php declare(strict_types=1);

namespace Tests\Unit\App\Providers;

use Mollie\OAuth2\Client\Provider\Mollie;
use Tests\TestCase;

class OAuthClientServiceProviderTest extends TestCase
{
    public function testEnsureMollieClientIsRegisteredIntoContainer(): void
    {
        $client = $this->app->get(Mollie::class);

        $this->assertInstanceOf(Mollie::class, $client);
    }
}
