<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\MollieAccessToken;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Token\AccessToken;
use Mollie\OAuth2\Client\Provider\Mollie;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    private const ACCESS_TOKEN = 'access_abc123';
    private const REFRESH_TOKEN = 'refresh_abc123';

    /** @var Mollie|MockObject */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));

        $this->client = $this->createMock(Mollie::class);
        $this->app->bind(Mollie::class, function (): Mollie {
            return $this->client;
        });
    }

    public function testWhenUserIsNotConnectedToMollieThenRedirectToConnectEndpointStatus(): void
    {
        $this->withoutMiddleware();

        $response = $this->json('GET', 'settings/payment');

        $response->assertRedirect(url('settings/payment/connect-to-mollie'));
    }

    public function testWhenTokenIsExpiredThenRefresh(): void
    {
        $this->createAccessTokenForTest();
        $this->client->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'token_abc123_new',
            'expires_in' => strtotime('2019-10-01 09:50:34'),
        ]));

        $this->json('GET', 'settings/payment');

        $this->assertDatabaseHas('mollie_access_tokens', [
            'user_id' => 1,
            'access_token' => 'token_abc123_new',
            'refresh_token' => 'refresh_abc123',
        ]);
    }

    private function createAccessTokenForTest(): void
    {
        MollieAccessToken::create([
            'user_id' => 1,
            'access_token' => self::ACCESS_TOKEN,
            'refresh_token' => self::REFRESH_TOKEN,
            'expires_at' => new DateTime('1970-01-01 10:10:58'),
        ]);
    }
}
