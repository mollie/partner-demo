<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\MollieAccessToken;
use App\OnboardingStatus;
use App\PaymentMethod;
use App\User;
use ArrayIterator;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Token\AccessToken;
use Mollie\Api\Endpoints\MethodEndpoint;
use Mollie\Api\Endpoints\OnboardingEndpoint;
use Mollie\Api\Endpoints\ProfileEndpoint;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Method;
use Mollie\Api\Resources\Onboarding;
use Mollie\Api\Resources\Profile;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    private const ACCESS_TOKEN = 'access_abc123';
    private const REFRESH_TOKEN = 'refresh_abc123';

    /** @var MollieOAuthClient|MockObject */
    private $oauthClient;

    /** @var MollieApiClient|MockObject */
    private $apiClient;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));

        $this->oauthClient = $this->createMock(MollieOAuthClient::class);
        $this->apiClient = $this->createMock(MollieApiClient::class);
        $this->apiClient->profiles = $this->createMock(ProfileEndpoint::class);
        $this->apiClient->methods = $this->createMock(MethodEndpoint::class);
        $this->apiClient->onboarding = $this->createMock(OnboardingEndpoint::class);

        $this->app->bind(MollieOAuthClient::class, function (): MollieOAuthClient {
            return $this->oauthClient;
        });
        $this->app->bind(MollieApiClient::class, function (): MollieApiClient {
            return $this->apiClient;
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
        $this->oauthClient->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'access_token_abc123_new',
            'expires_in' => strtotime('2019-10-01 09:50:34'),
        ]));

        $this->json('GET', 'settings/payment');

        $this->assertDatabaseHas('mollie_access_tokens', [
            'user_id' => 1,
            'access_token' => 'access_token_abc123_new',
            'refresh_token' => 'refresh_abc123',
        ]);
    }

    public function testWhenMethodsAreLoadedThenShowOnView(): void
    {
        $this->createAccessTokenForTest();
        $this->mockMollieClients();

        $this->apiClient->methods->expects($this->once())->method('allAvailable')->with(['profileId' => 'profile_1']);
        $this->apiClient->methods->expects($this->once())->method('allActive')->with(['profileId' => 'profile_1']);

        $response = $this->json('GET', route('payment_status'));

        $response->assertViewHas('status', new OnboardingStatus('completed', true, true, 'http://example/dashboard'));
        $response->assertViewHas('methods', [
            'ideal' => new PaymentMethod('ideal', 'iDEAL'),
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

    private function createMollieProfile(string $id, string $name, string $website): Profile
    {
        $method = new Profile($this->apiClient);
        $method->id = $id;
        $method->name = $name;
        $method->website = $website;

        return $method;
    }

    private function createMollieMethod(string $id, string $description): Method
    {
        $method = new Method($this->apiClient);

        $method->id = $id;
        $method->description = $description;

        return $method;
    }

    private function createMollieOnboarding(): Onboarding
    {
        $onboarding = new Onboarding($this->apiClient);
        $onboarding->_links = (object) ['dashboard' => (object) ['href' => 'http://example/dashboard']];
        $onboarding->status = 'completed';
        $onboarding->canReceivePayments = true;
        $onboarding->canReceiveSettlements = true;

        return $onboarding;
    }

    private function mockMollieClients(): void
    {
        $this->apiClient->profiles->method('page')->willReturn(new ArrayIterator([
            $this->createMollieProfile('profile_1', 'Profile 1', 'http://profile1.nl'),
            $this->createMollieProfile('profile_2', 'Profile 2', 'http://profile2.nl'),
        ]));

        $this->apiClient->methods->method('allAvailable')->willReturn(new  ArrayIterator([
            $this->createMollieMethod('applepay', 'Apple Pay'),
            $this->createMollieMethod('ideal', 'iDEAL'),
            $this->createMollieMethod('creditcard', 'Credit card'),
        ]));

        $this->apiClient->methods->method('allActive')->willReturn(new  ArrayIterator([
            $this->createMollieMethod('ideal', 'iDEAL'),
        ]));

        $this->apiClient->onboarding->method('get')->willReturn($this->createMollieOnboarding());

        $this->oauthClient->method('getAccessToken')->willReturn(new AccessToken([
            'access_token' => 'access_token_abc123_new',
            'expires_in' => strtotime('2019-10-01 09:50:34'),
        ]));
    }
}
