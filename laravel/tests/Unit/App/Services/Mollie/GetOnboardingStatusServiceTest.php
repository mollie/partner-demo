<?php declare(strict_types=1);

namespace Tests\Unit\App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\OnboardingStatus;
use App\Services\Mollie\GetOnboardingStatusService;
use App\User;
use Mollie\Api\Endpoints\OnboardingEndpoint;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Onboarding;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class GetOnboardingStatusServiceTest extends TestCase
{
    /** @var MollieApiClient|MockObject */
    private $apiClient;

    /** @var MollieApiClientFactory|MockObject */
    private $factory;

    /** @var GetOnboardingStatusService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiClient = $this->createMock(MollieApiClient::class);
        $this->factory = $this->createMock(MollieApiClientFactory::class);
        $this->factory->method('createForUser')->willReturn($this->apiClient);

        $this->service = new GetOnboardingStatusService($this->factory);
    }

    public function testGetOnboardingStatus(): void
    {
        $user = new User();
        $this->mockOnboardingOnApiClient();

        $status = $this->service->getOnboardingStatus($user);

        $this->assertEquals(new OnboardingStatus('completed', true, true, 'http://localhost/dashboard'), $status);
    }

    private function mockOnboardingOnApiClient()
    {
        $onboarding = new Onboarding($this->apiClient);
        $onboarding->_links = (object) ['dashboard' => (object) ['href' => 'http://localhost/dashboard']];
        $onboarding->status = 'completed';
        $onboarding->canReceivePayments = true;
        $onboarding->canReceiveSettlements = true;

        $this->apiClient->onboarding = $this->createMock(OnboardingEndpoint::class);
        $this->apiClient->onboarding->method('get')->willReturn($onboarding);
    }
}
