<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Mollie\OAuth2\Client\Provider\Mollie;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class OAuthConfirmTest extends TestCase
{
    use RefreshDatabase;

    private const OAUTH_CODE = 'auth_abc1233';

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

    public function testWhenMollieResponseIsValidThenMerchantGetsRedirectedToMollie(): void
    {
        $this->client->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'token_abc123',
            'refresh_token' => 'refresh_abc123',
            'expires_in' => strtotime('2019-10-01 09:50:34'),
        ]));

        $response = $this->json('GET', sprintf('settings/payment/oauth/confirm/%s', self::OAUTH_CODE));

        $response->assertRedirect(url('/settings/payment/oauth/redirect'));
        $this->assertDatabaseHas('mollie_access_tokens', [
            'user_id' => 1,
            'access_token' => 'token_abc123',
            'refresh_token' => 'refresh_abc123',
        ]);
    }

    public function testWhenMollieThrowsExceptionThenRedirectToSettingsPage()
    {
        $exception = new IdentityProviderException('Mollie Exception', 5555, []);
        $this->client->method('getAccessToken')->willThrowException($exception);

        $response = $this->json('GET', sprintf('settings/payment/oauth/confirm/%s', self::OAUTH_CODE));

        $response
            ->assertRedirect(url('/settings/payment'))
            ->assertSessionHasErrors([
                'code' => 5555,
                'message' => 'Mollie Exception',
            ]);
    }
}
