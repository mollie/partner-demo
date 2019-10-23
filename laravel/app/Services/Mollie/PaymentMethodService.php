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
        $methods = [];

        $methodsEnabled = $client->methods->allActive(['profileId' => $profile->getId()]);

        foreach ($methodsEnabled as $method) {
            $methods[$method->id] = $this->createPaymentMethod($method);
        }

        return $methods;
    }

    private function createPaymentMethod(Method $method): PaymentMethod
    {
        return new PaymentMethod(
            $method->id,
            $method->description
        );
    }
}
