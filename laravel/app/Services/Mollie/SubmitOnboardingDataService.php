<?php

namespace App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\User;

class SubmitOnboardingDataService
{
    /**
     * @var MollieApiClientFactory
     */
    private $apiClientFactory;

    public function __construct(MollieApiClientFactory $apiClientFactory)
    {
        $this->apiClientFactory = $apiClientFactory;
    }

    public function submitOnboardingData(User $user): void
    {
        $apiClient = $this->apiClientFactory->createForUser($user);

        $apiClient->onboarding->submit([
            "organization" => [
                "name" => $user->company_name
            ],
            "profile" => [
                "website" => $user->website
            ]
        ]);
    }
}
