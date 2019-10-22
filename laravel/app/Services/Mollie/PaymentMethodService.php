<?php declare(strict_types=1);

namespace App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\PaymentMethod;
use App\PaymentProfile;
use App\User;
use Mollie\Api\Resources\Method;

class PaymentMethodService
{
    /** @var MollieApiClientFactory */
    private $clientFactory;

    public function __construct(MollieApiClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @return PaymentMethod[]
     */
    public function loadFromProfile(User $user, PaymentProfile $profile): array
    {
        $client = $this->clientFactory->createForUser($user);

        $methods = iterator_to_array($client->methods->allAvailable(['profileId' => $profile->getId()]));

        return array_map(function (Method $method): PaymentMethod {
            return new PaymentMethod(
                $method->id,
                $method->description,
                $method->image->svg,
                (bool) array_rand([0, 1])
            );
        }, $methods);
    }
}
