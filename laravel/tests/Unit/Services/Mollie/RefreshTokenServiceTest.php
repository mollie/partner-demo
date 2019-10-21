<?php declare(strict_types=1);

namespace Tests\Unit\Services\Mollie;

use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Clock;
use App\Services\Mollie\RefreshTokenService;
use DateTime;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Mollie\OAuth2\Client\Provider\Mollie;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class RefreshTokenServiceTest extends TestCase
{
    private const REFRESH_TOKEN = 'abc-123';
    private const NEW_ACCESS_TOKEN = 'new-abc-123';
    private const EXPIRES_AT = '2020-01-01 09:09:09';

    /** @var AccessTokenInterface|MockObject */
    private $mollieResponse;

    /** @var Mollie|MockObject */
    private $mollieClient;

    /** @var MollieAccessTokenRepository|MockObject */
    private $repository;

    /** @var RefreshTokenService */
    private $service;

    protected function setUp(): void
    {
        $this->mollieResponse = $this->createMock(AccessTokenInterface::class);
        $this->mollieResponse->method('getExpires')->willReturn(strtotime(self::EXPIRES_AT));
        $this->mollieResponse->method('getToken')->willReturn(self::NEW_ACCESS_TOKEN);
        $this->mollieClient = $this->createMock(Mollie::class);
        $this->mollieClient->method('getAccessToken')->willReturn($this->mollieResponse);

        $this->repository = $this->createMock(MollieAccessTokenRepository::class);

        $this->service = new RefreshTokenService($this->mollieClient, $this->repository, new Clock());
    }

    public function testGivenATokenConnectToMollieToGetUpdatedAccessTokenValue(): void
    {
        $accessToken = new MollieAccessToken(['refresh_token' => self::REFRESH_TOKEN]);

        $this->mollieClient
            ->expects($this->once())
            ->method('getAccessToken')
            ->with('refresh_token', ['refresh_token' => self::REFRESH_TOKEN]);

        $this->service->refresh($accessToken);
    }

    public function testGivenAResponseThenSaveUpdatedValues(): void
    {
        $accessToken = new MollieAccessToken(['refresh_token' => self::REFRESH_TOKEN]);

        $this->repository
            ->expects($this->once())
            ->method('update')
            ->with(new MollieAccessToken([
                'access_token' => self::NEW_ACCESS_TOKEN,
                'refresh_token' => self::REFRESH_TOKEN,
                'expires_at' => new DateTime(self::EXPIRES_AT),
            ]));

        $this->service->refresh($accessToken);
    }
}
