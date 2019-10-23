<?php declare(strict_types=1);

namespace Tests\Unit\App\Services\Mollie;

use App\Factories\MollieApiClientFactory;
use App\PaymentProfile;
use App\Services\Mollie\UserPaymentProfileService;
use App\User;
use ArrayIterator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mollie\Api\Endpoints\ProfileEndpoint;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Profile;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class UserPaymentProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var ProfileEndpoint|MockObject */
    private $profile;

    /** @var MollieApiClient|MockObject */
    private $client;

    /** @var UserPaymentProfileService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profile = $this->createMock(ProfileEndpoint::class);

        $this->client = $this->createMock(MollieApiClient::class);
        $this->client->profiles = $this->profile;

        $factory = $this->createMock(MollieApiClientFactory::class);
        $factory->method('createForUser')->willReturn($this->client);

        $this->service = new UserPaymentProfileService($factory);
    }

    public function testWhenApiPaymentProfilesAreLoadedThenMapIntoPaymentProfiles(): void
    {
        $this->profile->method('page')->willReturn(new ArrayIterator([
            $this->createMollieMethod('profile_123', 'Profile 123', 'http://profile123.nl'),
            $this->createMollieMethod('profile_456', 'Profile 456', 'http://profile456.nl'),
            $this->createMollieMethod('profile_789', 'Profile 789', 'http://profile789.nl'),
        ]));

        $profiles = $this->service->loadUserProfile(new User());

        $this->assertEquals([
            new PaymentProfile('profile_123', 'Profile 123', 'http://profile123.nl'),
            new PaymentProfile('profile_456', 'Profile 456', 'http://profile456.nl'),
            new PaymentProfile('profile_789', 'Profile 789', 'http://profile789.nl'),
        ], $profiles);
    }

    private function createMollieMethod(string $id, string $name, string $website): Profile
    {
        $method = new Profile($this->client);
        $method->id = $id;
        $method->name = $name;
        $method->website = $website;

        return $method;
    }
}
