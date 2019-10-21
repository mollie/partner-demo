<?php

namespace App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\User;
use Mollie\Api\Resources\Onboarding;

class GetOnboardingStatusService
{
    /**
     * @var MollieApiClientFactory
     */
    private $apiClientFactory;

    public function __construct(MollieApiClientFactory $apiClientFactory)
    {
        $this->apiClientFactory = $apiClientFactory;
    }

    public function getOnboardingStatus(User $user): Onboarding
    {
        $apiClient = $this->apiClientFactory->createForUser($user);

        return $apiClient->onboarding->get();
    }
}
