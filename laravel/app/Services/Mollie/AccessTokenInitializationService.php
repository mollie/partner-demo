<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\ClockService;
use DateTime;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;

class AccessTokenInitializationService
{
    /** @var MollieOAuthClient */
    private $mollieClient;

    /** @var MollieAccessTokenRepository */
    private $repository;

    /** @var ClockService */
    private $clock;

    public function __construct(
        MollieOAuthClient $mollieClient,
        MollieAccessTokenRepository $repository,
        ClockService $clock
    ) {
        $this->mollieClient = $mollieClient;
        $this->repository = $repository;
        $this->clock = $clock;
    }

    /**
     * @throws IdentityProviderException
     */
    public function initialize(string $code): void
    {
        $response = $this->mollieClient->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        $date = new DateTime();
        $date->setTimestamp($response->getExpires());

        $mollieAccessToken = new MollieAccessToken();
        $mollieAccessToken->user_id = Auth::id();
        $mollieAccessToken->access_token = $response->getToken();
        $mollieAccessToken->refresh_token = $response->getRefreshToken();
        $mollieAccessToken->expires_at = $this->clock->createFromTimestamp($response->getExpires());

        $this->repository->create($mollieAccessToken);
    }
}
