<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
use App\Factories\MollieApiClientFactory;
use App\MollieStatus;
use App\Repositories\MollieAccessTokenRepository;
use App\User;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class StatusService
{
    /** @var MollieAccessTokenRepository */
    private $repository;

    /** @var RefreshTokenService */
    private $refreshTokenService;

    /**
     * @var MollieApiClientFactory
     */
    private $apiClientFactory;

    public function __construct(
        MollieAccessTokenRepository $repository,
        RefreshTokenService $refreshTokenService,
        MollieApiClientFactory $apiClientFactory
    ) {
        $this->repository = $repository;
        $this->refreshTokenService = $refreshTokenService;
        $this->apiClientFactory = $apiClientFactory;
    }

    /**
     * @throws UserNotConnectedToMollie
     * @throws IdentityProviderException
     */
    public function getMollieStatus(User $user): MollieStatus
    {
        $accessToken = $this->repository->getUserAccessToken($user);

        if (!$accessToken) {
            throw new UserNotConnectedToMollie($user);
        }

        if ($accessToken->isExpired()) {
            $this->refreshTokenService->refresh($accessToken);
        }

        $apiClient = $this->apiClientFactory->createForUser($user);
        $response = $apiClient->onboarding->get();

        return MollieStatus::fromOnboardingApiResponse($response);
    }
}
