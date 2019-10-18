<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Clock;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;

class RefreshTokenService
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

    public function refresh(MollieAccessToken $accessToken): void
    {
        $response = $this->mollieClient->getAccessToken('refresh_token', [
            'refresh_token' => $accessToken->refresh_token,
        ]);

        $accessToken->expires_at = $this->clock->createFromTimestamp($response->getExpires());
        $accessToken->access_token = $response->getToken();

        $this->repository->update($accessToken);
    }
}
