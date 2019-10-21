<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Exceptions\UserAlreadyConnectedToMollie;
use App\Repositories\MollieAccessTokenRepository;
use App\User;
use Exception;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;

class AuthorizationLinkService
{
    /** @var MollieOAuthClient */
    private $mollieClient;

    /** @var MollieAccessTokenRepository */
    private $repository;

    public function __construct(MollieOAuthClient $mollieClient, MollieAccessTokenRepository $repository)
    {
        $this->mollieClient = $mollieClient;
        $this->repository = $repository;
    }

    /**
     * @throws UserAlreadyConnectedToMollie
     */
    public function getLink(User $user): string
    {
        $accessToken = $this->repository->getUserAccessToken($user);

        if ($accessToken) {
            throw new UserAlreadyConnectedToMollie($user);
        }

        return $this->mollieClient->getAuthorizationUrl([
            "scope" => $this->getRequiredScopes()
        ]);
    }

    private function getRequiredScopes(): array
    {
        return [
            MollieOAuthClient::SCOPE_ONBOARDING_READ,
            MollieOAuthClient::SCOPE_ONBOARDING_WRITE,
            MollieOAuthClient::SCOPE_ORGANIZATIONS_READ,
            MollieOAuthClient::SCOPE_ORGANIZATIONS_WRITE,
        ];
    }
}
