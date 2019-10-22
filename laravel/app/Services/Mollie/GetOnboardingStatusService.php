<?php

namespace App\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
use App\Factories\MollieApiClientFactory;
use App\OnboardingStatus;
use App\User;
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;

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

    /**
     * @throws UserNotConnectedToMollie
     */
    public function getOnboardingStatus(User $user): OnboardingStatus
    {
        $apiClient = $this->apiClientFactory->createForUser($user);

        $onboardingResponse = $apiClient->onboarding->get();

        return new OnboardingStatus(
            $onboardingResponse->status,
            $onboardingResponse->canReceivePayments,
            $onboardingResponse->canReceiveSettlements,
            $onboardingResponse->_links->dashboard->href
        );
    }
}
