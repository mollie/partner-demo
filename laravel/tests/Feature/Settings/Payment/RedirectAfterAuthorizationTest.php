<?php

namespace Tests\Feature\Settings\Payment;

use App\MollieAccessToken;
use App\OnboardingStatus;
use App\Services\Mollie\GetOnboardingStatusService;
use App\Services\Mollie\SubmitOnboardingDataService;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class RedirectAfterAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private const ACCESS_TOKEN = 'access_abc123';
    private const REFRESH_TOKEN = 'refresh_abc123';

    /**
     * @var GetOnboardingStatusService|MockObject
     */
    protected $onboardingStatusService;
    /**
     * @var SubmitOnboardingDataService|MockObject
     */
    protected $submitOnboardingDataService;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));

        $this->createAccessTokenForTest();

        $this->onboardingStatusService = $this->createMock(GetOnboardingStatusService::class);

        $this->app->bind(GetOnboardingStatusService::class, function (): GetOnboardingStatusService {
            return $this->onboardingStatusService;
        });

        $this->submitOnboardingDataService = $this->createMock(SubmitOnboardingDataService::class);

        $this->app->bind(SubmitOnboardingDataService::class, function (): SubmitOnboardingDataService {
            return $this->submitOnboardingDataService;
        });
    }

    public function testWhenMollieDoesNotNeedDataTheyAreRedirectedToThePaymentStatusPageAndOnboardingDataIsNotSubmitted(): void
    {
        $this->onboardingStatusService
            ->method('getOnboardingStatus')
            ->willReturn(new OnboardingStatus(
                "completed",
                true,
                true,
                "https://wwww.mollie.com/dashboard"
            ));

        $this->submitOnboardingDataService
            ->expects($this->never())
            ->method("submitOnboardingData");

        $response = $this->json('GET', '/settings/payment/oauth/redirect');

        $response->assertRedirect(url('/settings/payment'));
    }

    public function testWhenMollieNeedsDataWeSubmitOnboardingDataAndRedirectToMollieOnboarding()
    {
        $this->onboardingStatusService
            ->method('getOnboardingStatus')
            ->willReturn(new OnboardingStatus(
                "needs-data",
                false,
                false,
                "https://wwww.mollie.com/dashboard"
            ));

        $this->submitOnboardingDataService
            ->expects($this->once())
            ->method("submitOnboardingData");

        $response = $this->json('GET', '/settings/payment/oauth/redirect');

        $response->assertRedirect('https://wwww.mollie.com/dashboard');
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
