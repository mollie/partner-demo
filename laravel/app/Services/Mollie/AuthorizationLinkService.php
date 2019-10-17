<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Repositories\MollieAccessTokenRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
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
     * @throws Exception
     */
    public function getLink(): string
    {
        $user = Auth::user();
        $accessToken = $this->repository->getUserAccessToken($user);

        if ($accessToken) {
            throw new Exception('user is already connected');
        }

        return $this->mollieClient->getAuthorizationUrl();
    }
}
