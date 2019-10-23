<?php declare(strict_types=1);

namespace Tests\Unit\App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\PaymentProfile;
use App\Services\Mollie\PaymentMethodService;
use App\User;
use Mollie\Api\Endpoints\MethodEndpoint;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Method;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Tests\TestCase;

class PaymentMethodServiceTest extends TestCase
{
    /** @var MollieApiClient|MockObject */
    private $client;

    /** @var PaymentMethodService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(MollieApiClient::class);
        $this->client->methods = $this->createMock(MethodEndpoint::class);
        $factory = $this->createMock(MollieApiClientFactory::class);
        $factory->method('createForUser')->willReturn($this->client);

        $this->service = new PaymentMethodService($factory);
    }

    public function testShouldMergeAvailableAndActiveMethods(): void
    {
        $this->client->methods->method('allAvailable')->willReturn([
            $this->createMollieMethod('applepay', 'Apple Pay'),
            $this->createMollieMethod('ideal', 'iDEAL'),
            $this->createMollieMethod('creditcard', 'Credit card'),
        ]);
        $this->client->methods->method('allActive')->willReturn([
            $this->createMollieMethod('ideal', 'iDEAL'),
        ]);

        $methods = $this->service->loadFromProfile(new User(), new PaymentProfile('', '', ''));

        $this->assertEquals(
            [
                'applepay' => new PaymentMethod('applepay', 'Apple Pay', 'image.svg', false),
                'ideal' => new PaymentMethod('ideal', 'iDEAL', 'image.svg', true),
                'creditcard' => new PaymentMethod('creditcard', 'Credit card', 'image.svg', false),
            ],
            $methods
        );
    }

    /**
     * @return Method
     */
    private function createMollieMethod(string $id, string $description): Method
    {
        $method = new Method($this->client);

        $method->id = $id;
        $method->description = $description;
        $method->image = new stdClass();
        $method->image->svg = 'image.svg';

        return $method;
    }
}
