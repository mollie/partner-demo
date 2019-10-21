<?php

namespace App\Factories;

use App\Repositories\MollieAccessTokenRepository;
use App\User;
use Mollie\Api\MollieApiClient;

class MollieApiClientFactory
{
    /**
     * @var MollieApiClient|null
     */
    private static $mollieApiClient;

    /**
     * @var MollieAccessTokenRepository
     */
    private $accessTokenRepository;

    public function __construct(MollieAccessTokenRepository $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function createForUser(User $user): MollieApiClient
    {
        $mollieApiClient = $this->getMollieApiClient();

        $accessToken = $this->accessTokenRepository->getUserAccessToken($user);

        $mollieApiClient->setAccessToken($accessToken->access_token);

        return $mollieApiClient;
    }

    private function getMollieApiClient(): MollieApiClient
    {
        if (self::$mollieApiClient) {
            return self::$mollieApiClient;
        }

        return new MollieApiClient();
    }

}
