<?php

namespace App\Http\Middleware\Mollie;

use App\Repositories\MollieAccessTokenRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotConnectedToMollieMiddleware
{
    /** @var MollieAccessTokenRepository */
    private $mollieTokenRepository;

    public function __construct(MollieAccessTokenRepository $mollieTokenRepository)
    {
        $this->mollieTokenRepository = $mollieTokenRepository;
    }

    public function handle(Request $request, Closure $next)
    {
        $token = $this->mollieTokenRepository->getUserAccessToken(Auth::user());

        if ($token) {
            return redirect(route('payment_status'));
        }

        return $next($request);
    }
}
