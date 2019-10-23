<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Clock;
use App\User;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;

class AuthorizationCodeService
{
    /** @var MollieOAuthClient */
    private $mollieClient;

    /** @var MollieAccessTokenRepository */
    private $repository;

    /** @var Clock */
    private $clock;

    public function __construct(
        MollieOAuthClient $mollieClient,
        MollieAccessTokenRepository $repository,
        Clock $clock
    ) {
        $this->mollieClient = $mollieClient;
        $this->repository = $repository;
        $this->clock = $clock;
    }

    /**
     * @throws IdentityProviderException
     */
    public function authorize(string $code, User $user): void
    {
        $response = $this->mollieClient->getAccessToken('authorization_code', [
            'code' => $code,
        ]);
        $mollieAccessToken = new MollieAccessToken();
        $mollieAccessToken->user_id = $user->id;
        $mollieAccessToken->access_token = $response->getToken();
        $mollieAccessToken->refresh_token = $response->getRefreshToken();
        $mollieAccessToken->expires_at = $this->clock->createFromTimestamp($response->getExpires());

        $this->repository->create($mollieAccessToken);
    }
}
