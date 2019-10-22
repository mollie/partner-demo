<?php declare(strict_types=1);

namespace Tests\Unit\App\Services\Mollie;

use App\Exceptions\UserNotConnectedToMollie;
use App\MollieAccessToken;
use App\OnboardingStatus;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Mollie\GetOnboardingStatusService;
use App\Services\Mollie\RefreshTokenService;
use App\Services\Mollie\StatusService;
use App\User;
use DateTime;
use Mollie\Api\MollieApiClient;
use Mollie\Api\Resources\Onboarding;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class StatusServiceTest extends TestCase
{
    /**
     * @var GetOnboardingStatusService|MockObject
     */
    private $getOnboardingStatusService;

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
        $this->getOnboardingStatusService = $this->createMock(GetOnboardingStatusService::class);

        $this->getOnboardingStatusService
            ->method("getOnboardingStatus")
            ->willReturn($this->getOnboardingStatusMock());

        $this->service = new StatusService($this->repository, $this->refreshTokenService, $this->getOnboardingStatusService);
    }

    public function testWhenCantFindAccessTokenThenThrowException(): void
    {
        $this->repository->method('getUserAccessToken')->willReturn(null);

        $this->expectException(UserNotConnectedToMollie::class);

        $this->service->getOnboardingStatus(new User());
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

        $this->service->getOnboardingStatus(new User());
    }

    public function testWhenAccessTokenIsValidThenDoNotRefreshToken(): void
    {
        $expiresAt = new DateTime('2019-02-01 09:34:56');
        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);
        $this->repository->method('getUserAccessToken')->willReturn($accessToken);

        $this->refreshTokenService
            ->expects($this->never())
            ->method('refresh');

        $this->service->getOnboardingStatus(new User());
    }

    public function testWhenUserIsConnectedThenReturnStatus(): void
    {
        $expiresAt = new DateTime('2019-02-01 09:34:56');
        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);
        $this->repository->method('getUserAccessToken')->willReturn($accessToken);

        $status = $this->service->getOnboardingStatus(new User());

        $this->assertEquals($this->getOnboardingStatusMock(), $status);
    }

    private function getOnboardingStatusMock(): OnboardingStatus
    {
        return new OnboardingStatus(
            "needs-data",
            true,
            true,
            "https://www.mollie.com/dashboard"
        );
    }
}
