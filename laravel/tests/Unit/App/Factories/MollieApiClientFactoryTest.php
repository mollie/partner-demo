<?php declare(strict_types=1);

namespace Tests\Unit\App\Factories;

use App\Exceptions\UserNotConnectedToMollie;
use App\Factories\MollieApiClientFactory;
use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\User;
use Mollie\Api\MollieApiClient;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class MollieApiClientFactoryTest extends TestCase
{
    const ACCESS_TOKEN = 'access_123abc';
    /** @var MollieAccessTokenRepository|MockObject */
    private $repository;

    /** @var MollieApiClientFactory */
    private $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(MollieAccessTokenRepository::class);
        $this->factory = new MollieApiClientFactory($this->repository);
    }

    public function testWhenUserIsConnectedToMollieThenReturnClient(): void
    {
        $this->repository
            ->method('getUserAccessToken')
            ->willReturn(new MollieAccessToken(['access_token' => self::ACCESS_TOKEN]));

        $client = $this->factory->createForUser(new User());

        $this->assertEquals($this->expectedClient(), $client);
    }

    public function testWhenClientWasAlreadyCreatedThenGetFromCacheAndDoNotCreateAgain(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('getUserAccessToken')
            ->willReturn(new MollieAccessToken(['access_token' => self::ACCESS_TOKEN]));

        $this->factory->createForUser(new User());
        $this->factory->createForUser(new User());
    }

    public function testWhenUserIsNotConnectedToMollieThenThrowException(): void
    {
        $this->repository
            ->method('getUserAccessToken')
            ->willReturn(null);

        $this->expectException(UserNotConnectedToMollie::class);

        $this->factory->createForUser(new User());
    }

    private function expectedClient(): MollieApiClient
    {
        $expected = new MollieApiClient();
        $expected->setAccessToken(self::ACCESS_TOKEN);

        return $expected;
    }
}
