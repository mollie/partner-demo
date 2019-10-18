<?php

namespace App\Http\Middleware\Mollie;

use App\Repositories\MollieAccessTokenRepository;
use App\Services\AuthenticatedUserLoader;
use Closure;
use Illuminate\Http\Request;

class ConnectedToMollieMiddleware
{
    /** @var MollieAccessTokenRepository */
    private $mollieTokenRepository;

    /** @var AuthenticatedUserLoader */
    private $userLoader;

    public function __construct(MollieAccessTokenRepository $mollieTokenRepository, AuthenticatedUserLoader $userLoader)
    {
        $this->mollieTokenRepository = $mollieTokenRepository;
        $this->userLoader = $userLoader;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $this->mollieTokenRepository->getUserAccessToken($this->userLoader->load());

        if (!$token) {
            return redirect(route('connect_to_mollie'));
        }

        return $next($request);
    }
}
