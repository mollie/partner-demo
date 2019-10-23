<?php

namespace App\Factories;

use App\Exceptions\UserNotConnectedToMollie;
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

    /**
     * @throws UserNotConnectedToMollie
     */
    public function createForUser(User $user): MollieApiClient
    {
        if (!$this->mollieApiClient) {
            $this->mollieApiClient = $this->create($user);
        }

        return $this->mollieApiClient;
    }

    /**
     * @throws UserNotConnectedToMollie
     */
    private function create(User $user): MollieApiClient
    {
        $mollieAccessToken = $this->accessTokenRepository->getUserAccessToken($user);

        if (!$mollieAccessToken) {
            throw new UserNotConnectedToMollie($user);
        }

        $client = new MollieApiClient();
        $client->setAccessToken($mollieAccessToken->access_token);

        return $client;
    }
}
