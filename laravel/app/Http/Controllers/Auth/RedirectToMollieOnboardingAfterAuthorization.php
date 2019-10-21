<?php


namespace App\Http\Controllers\Auth;


use App\Factories\MollieApiClientFactory;
use App\MollieStatus;
use App\Services\AuthenticatedUserLoader;

class RedirectToMollieOnboardingAfterAuthorization
{
    /**
     * @var MollieApiClientFactory
     */
    private $apiClientFactory;

    /**
     * @var AuthenticatedUserLoader
     */
    private $userLoader;

    public function __construct(MollieApiClientFactory $apiClientFactory, AuthenticatedUserLoader $userLoader)
    {
        $this->apiClientFactory = $apiClientFactory;
        $this->userLoader = $userLoader;
    }

    public function __invoke()
    {
        $apiClient = $this->apiClientFactory->createForUser($this->userLoader->load());
        $response = $apiClient->onboarding->get();

        $mollieStatus = MollieStatus::fromOnboardingApiResponse($response);

        return redirect($mollieStatus->getDashboardLink());
    }


}
