<?php declare(strict_types=1);

namespace Tests\Unit\Services\Mollie;

use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Clock;
use App\Services\Mollie\AuthorizationCodeService;
use App\User;
use DateTime;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Mollie\OAuth2\Client\Provider\Mollie;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AuthorizationCodeServiceTest extends TestCase
{
    private const ACCESS_CODE = 'abc-123';
    private const ACCESS_TOKEN = 'new-abc-123';
    private const EXPIRES_AT = '2020-01-01 09:09:09';
    private const REFRESH_TOKEN = 'abc-123';

    /** @var AccessTokenInterface|MockObject */
    private $mollieResponse;

    /** @var Mollie|MockObject */
    private $mollieClient;

    /** @var MollieAccessTokenRepository|MockObject */
    private $repository;

    /** @var AuthorizationCodeService */
    private $service;

    protected function setUp(): void
    {
        $this->mollieResponse = $this->createMock(AccessTokenInterface::class);
        $this->mollieResponse->method('getExpires')->willReturn(strtotime(self::EXPIRES_AT));
        $this->mollieResponse->method('getToken')->willReturn(self::ACCESS_TOKEN);
        $this->mollieResponse->method('getRefreshToken')->willReturn(self::REFRESH_TOKEN);
        $this->mollieClient = $this->createMock(Mollie::class);
        $this->mollieClient->method('getAccessToken')->willReturn($this->mollieResponse);

        $this->repository = $this->createMock(MollieAccessTokenRepository::class);

        $this->service = new AuthorizationCodeService($this->mollieClient, $this->repository, new Clock());
    }

    public function testGivenAnUserAndACodeThenCallMollieToGetAuthorizationValues(): void
    {
        $code = self::ACCESS_CODE;

        $this->mollieClient
            ->expects($this->once())
            ->method('getAccessToken')
            ->with('authorization_code', ['code' => $code]);

        $this->service->authorize($code, new User());
    }

    public function testGivenMollieResponseThenSaveMollieAccessToken(): void
    {
        $user = new User();
        $user->id = 2020;

        $this->repository
            ->expects($this->once())
            ->method('create')
            ->with(new MollieAccessToken([
                'user_id' => 2020,
                'access_token' => self::ACCESS_TOKEN,
                'refresh_token' => self::REFRESH_TOKEN,
                'expires_at' => new DateTime(self::EXPIRES_AT),
            ]));

        $this->service->authorize(self::ACCESS_CODE, $user);
    }
}
