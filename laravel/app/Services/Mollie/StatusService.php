<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
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

    public function __construct(MollieAccessTokenRepository $repository, RefreshTokenService $refreshTokenService)
    {
        $this->repository = $repository;
        $this->refreshTokenService = $refreshTokenService;
    }

    /**
     * @throws UserNotConnectedToMollie
     * @throws IdentityProviderException
     */
    public function status(User $user)
    {
        $accessToken = $this->repository->getUserAccessToken($user);

        if (!$accessToken) {
            throw new UserNotConnectedToMollie($user);
        }

        if ($accessToken->isExpired()) {
            $this->refreshTokenService->refresh($accessToken);
        }

        // Call status API

        // Based on status switch context

        return new MollieStatus($accessToken);
    }
}
