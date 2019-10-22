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
    private $mollieApiClient;

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
        if (!$this->mollieApiClient) {
            $this->mollieApiClient = $this->createClientForUser($user);
        }

        return $this->mollieApiClient;
    }

    private function createClientForUser(User $user): MollieApiClient
    {
        $mollieAccessToken = $this->accessTokenRepository->getUserAccessToken($user);

        $client = new MollieApiClient();
        $client->setAccessToken($mollieAccessToken->access_token);

        return $client;
    }
}
