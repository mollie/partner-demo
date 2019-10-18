<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Http\Controllers\Controller;
use App\Services\Mollie\AuthorizationLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectedToMollieController extends Controller
{
    /** @var AuthorizationLinkService */
    private $authorizeService;

    public function __construct(AuthorizationLinkService $authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }

    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return view('settings.payment.connect', [
            'authLink' => $this->authorizeService->getLink($user),
        ]);
    }
}
