<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\MollieAccessToken;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mollie\OAuth2\Client\Provider\Mollie;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ConnectToMollieTest extends TestCase
{
    use RefreshDatabase;

    private const ACCESS_TOKEN = 'access_abc123';
    private const REFRESH_TOKEN = 'refresh_abc123';
    const CONNECT_LINK = 'https://www.mollie.com/oauth2/authorize';

    /** @var Mollie|MockObject */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));

        $this->withoutMiddleware();

        $this->client = $this->createMock(Mollie::class);
        $this->app->bind(Mollie::class, function (): Mollie {
            return $this->client;
        });
    }

    public function testWhenUserIsAlreadyConnectedThenRedirectToPaymentStatus(): void
    {
        MollieAccessToken::create([
            'user_id' => 1,
            'access_token' => self::ACCESS_TOKEN,
            'refresh_token' => self::REFRESH_TOKEN,
            'expires_at' => new DateTime('1970-01-01 10:10:58'),
        ]);

        $response = $this->json('GET', route('connect_to_mollie'));

        $response
            ->assertRedirect(route('payment_status'))
            ->assertSessionHas('message', 'User #1 is already connected to Mollie');
    }

    public function testWhenUserIsNotRegisteredThenReturnViewWithConnectionButton(): void
    {
        $this->client->method('getAuthorizationUrl')->willReturn(self::CONNECT_LINK);

        $response = $this->json('GET', route('connect_to_mollie'));

        $response->assertViewHas('authLink', self::CONNECT_LINK);
    }
}
