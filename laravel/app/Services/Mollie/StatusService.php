<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
use App\OnboardingStatus;
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
     * @var GetOnboardingStatusService
     */
    private $onboardingStatusService;

    public function __construct(
        MollieAccessTokenRepository $repository,
        RefreshTokenService $refreshTokenService,
        GetOnboardingStatusService $onboardingStatusService
    ) {
        $this->repository = $repository;
        $this->refreshTokenService = $refreshTokenService;
        $this->onboardingStatusService = $onboardingStatusService;
    }

    /**
     * @throws UserNotConnectedToMollie
     * @throws IdentityProviderException
     */
    public function getOnboardingStatus(User $user): OnboardingStatus
    {
        $accessToken = $this->repository->getUserAccessToken($user);

        if (!$accessToken) {
            throw new UserNotConnectedToMollie($user);
        }

        if ($accessToken->isExpired()) {
            $this->refreshTokenService->refresh($accessToken);
        }

        return $this->onboardingStatusService->getOnboardingStatus($user);
    }
}
