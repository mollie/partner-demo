<?php

namespace Tests\Unit\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\Services\Mollie\SubmitOnboardingDataService;
use App\User;
use Mollie\Api\Endpoints\OnboardingEndpoint;
use Mollie\Api\MollieApiClient;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class SubmitOnboardingDataServiceTest extends TestCase
{
    /**
     * @var OnboardingEndpoint|MockObject
     */
    protected $onboardingEndpoint;

    /**
     * @var SubmitOnboardingDataService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->onboardingEndpoint = $this->createMock(OnboardingEndpoint::class);

        $apiClient = $this->createMock(MollieApiClient::class);
        $apiClient->onboarding = $this->onboardingEndpoint;

        $factory = $this->createMock(MollieApiClientFactory::class);
        $factory->method('createForUser')->willReturn($apiClient);

        $this->service = new SubmitOnboardingDataService($factory);
    }

    public function testSubmitOnboardingDataServiceTransmitsUserDataToMollie()
    {
        $user = new User();
        $user->website = "http://www.example.org";
        $user->company_name = "Amazing Platform";

        $this->onboardingEndpoint
            ->expects($this->once())
            ->method("submit")
            ->with([
                "organization" => [
                    "name" => $user->company_name
                ],
                "profile" => [
                    "website" => $user->website
                ]
            ]);

        $this->service->submitOnboardingData($user);
    }

}
