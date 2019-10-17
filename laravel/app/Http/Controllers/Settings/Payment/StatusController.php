<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Repositories\MollieAccessTokenRepository;
use App\Services\Mollie\RefreshTokenService;
use Illuminate\Support\Facades\Auth;

class StatusController
{
    /** @var MollieAccessTokenRepository */
    private $repository;

    /** @var RefreshTokenService */
    private $refreshTokenService;

    public function __construct(MollieAccessTokenRepository $repository, RefreshTokenService $refreshTokenService)
    {
        $this->repository = $repository;
        $this->refreshTokenService = $refreshTokenService;
    }

    public function __invoke()
    {
        $user = Auth::user();
        $accessToken = $this->repository->getUserAccessToken($user);

        if ($accessToken->isExpired()) {
            $this->refreshTokenService->refresh($accessToken);
        }

        return view('settings.payment.status');

        // Call status API

        // Based on status switch context
    }
}
