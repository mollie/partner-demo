<?php declare(strict_types=1);

namespace Tests\Unit\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
use App\MollieAccessToken;
use App\MollieStatus;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Mollie\RefreshTokenService;
use App\Services\Mollie\StatusService;
use App\User;
use DateTime;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class StatusServiceTest extends TestCase
{
    /** @var MollieAccessTokenRepository|MockObject */
    private $repository;

    /** @var RefreshTokenService|MockObject */
    private $refreshTokenService;

    /** @var StatusService */
    private $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MollieAccessTokenRepository::class);
        $this->refreshTokenService = $this->createMock(RefreshTokenService::class);

        $this->service = new StatusService($this->repository, $this->refreshTokenService);
    }

    public function testWhenCantFindAccessTokenThenThrowException(): void
    {
        $this->repository->method('getUserAccessToken')->willReturn(null);

        $this->expectException(UserNotConnectedToMollie::class);

        $this->service->status(new User());
    }

    public function testWhenAccessTokenIsExpiredThenRefresh(): void
    {
        $expiresAt = new DateTime('2018-12-31 09:34:56');
        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);
        $this->repository->method('getUserAccessToken')->willReturn($accessToken);

        $this->refreshTokenService
            ->expects($this->once())
            ->method('refresh')
            ->with($accessToken);

        $this->service->status(new User());
    }

    public function testWhenAccessTokenIsValidThenDoNotRefreshToken(): void
    {
        $expiresAt = new DateTime('2019-02-01 09:34:56');
        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);
        $this->repository->method('getUserAccessToken')->willReturn($accessToken);

        $this->refreshTokenService
            ->expects($this->never())
            ->method('refresh');

        $this->service->status(new User());
    }

    public function testWhenUserIsConnectedThenReturnStatus(): void
    {
        $expiresAt = new DateTime('2019-02-01 09:34:56');
        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);
        $this->repository->method('getUserAccessToken')->willReturn($accessToken);

        $status = $this->service->status(new User());

        $this->assertEquals(new MollieStatus($accessToken), $status);
    }
}
