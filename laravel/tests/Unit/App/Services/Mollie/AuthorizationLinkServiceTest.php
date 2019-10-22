<?php declare(strict_types=1);

namespace Tests\Unit\App\Services\Mollie;

use App\Exceptions\UserAlreadyConnectedToMollie;
use App\MollieAccessToken;
use App\Repositories\MollieAccessTokenRepository;
use App\Services\Mollie\AuthorizationLinkService;
use App\User;
use Mollie\OAuth2\Client\Provider\Mollie;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOAuthClient;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AuthorizationLinkServiceTest extends TestCase
{
    private const MOLLIE_URL = 'https://mollie.com';

    /** @var Mollie|MockObject */
    private $mollieClient;

    /** @var MollieAccessTokenRepository|MockObject */
    private $repository;

    /** @var AuthorizationLinkService */
    private $service;

    protected function setUp(): void
    {
        $this->mollieClient = $this->createMock(Mollie::class);
        $this->repository = $this->createMock(MollieAccessTokenRepository::class);

        $this->service = new AuthorizationLinkService($this->mollieClient, $this->repository);
    }

    public function testWhenUserIsAlreadyConnectedToMollieThenThrowException(): void
    {
        $this->repository->method('getUserAccessToken')->willReturn(new MollieAccessToken());

        $this->expectException(UserAlreadyConnectedToMollie::class);

        $this->service->getLink(new User());
    }

    public function testWhenUserIsNotConnectedThenGetOAuthLink(): void
    {
        $this->repository->method('getUserAccessToken')->willReturn(null);

        $this->mollieClient
            ->expects($this->once())
            ->method('getAuthorizationUrl')
            ->with(["scope" => $this->expectedScopes()])
            ->willReturn(self::MOLLIE_URL);

        $this->service->getLink(new User());
    }

    private function expectedScopes(): array
    {
        return [
            MollieOAuthClient::SCOPE_ONBOARDING_READ,
            MollieOAuthClient::SCOPE_ONBOARDING_WRITE,
            MollieOAuthClient::SCOPE_ORGANIZATIONS_READ,
            MollieOAuthClient::SCOPE_ORGANIZATIONS_WRITE,
            MollieOAuthClient::SCOPE_PROFILES_READ,
            MollieOAuthClient::SCOPE_PROFILES_WRITE,
            MollieOAuthClient::SCOPE_PAYMENTS_READ,
        ];
    }
}
