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

        $methodsAvailable = $client->methods->allAvailable(['profileId' => $profile->getId()]);
        $methodsEnabled = $client->methods->allActive(['profileId' => $profile->getId()]);

        foreach ($methodsAvailable as $method) {
            $methods[$method->id] = $this->createPaymentMethod($method, false);
        }

        foreach ($methodsEnabled as $method) {
            $methods[$method->id] = $this->createPaymentMethod($method, true);
        }

        return $methods;
    }

    private function createPaymentMethod(Method $method, bool $active): PaymentMethod
    {
        return new PaymentMethod(
            $method->id,
            $method->description,
            $method->image->svg,
            $active
        );
    }
}
