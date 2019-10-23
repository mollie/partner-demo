<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\PaymentProfile;
use App\User;
use Mollie\Api\Resources\Profile;

class UserPaymentProfileService
{
    /** @var MollieApiClientFactory */
    private $clientFactory;

    public function __construct(MollieApiClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @return PaymentProfile[]
     */
    public function loadUserProfile(User $user): array
    {
        $client = $this->clientFactory->createForUser($user);

        $profiles = iterator_to_array($client->profiles->page());

        return array_map(function (Profile $profile): PaymentProfile {
            return new PaymentProfile($profile->id, $profile->name, $profile->website);
        }, $profiles);
    }
}
