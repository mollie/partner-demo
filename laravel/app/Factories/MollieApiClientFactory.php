<?php

namespace App\Factories;

use App\Exceptions\UserNotConnectedToMollie;
use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\User;
use Mollie\Api\MollieApiClient;

class MollieApiClientFactory
{
    /**
     * @var MollieApiClient|null
     */
    private $mollieApiClient;

    /**
     * @var MollieAccessTokenRepository
     */
    private $accessTokenRepository;

    /**
     * @var MollieAccessToken
     */
    private $accessToken;

    public function __construct(MollieApiClient $mollieApiClient, MollieAccessTokenRepository $accessTokenRepository)
    {
        $this->mollieApiClient = $mollieApiClient;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * @throws UserNotConnectedToMollie
     */
    public function createForUser(User $user): MollieApiClient
    {
        if (!$this->accessToken) {
            $this->setUserAccessToken($user);
        }

        return $this->mollieApiClient;
    }

    /**
     * @throws UserNotConnectedToMollie
     */
    private function setUserAccessToken(User $user): void
    {
        $this->accessToken = $this->accessTokenRepository->getUserAccessToken($user);

        if (!$this->accessToken) {
            throw new UserNotConnectedToMollie($user);
        }

        $this->mollieApiClient->setAccessToken($this->accessToken->access_token);
    }
}
