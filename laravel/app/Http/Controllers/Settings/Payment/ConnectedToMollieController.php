<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Http\Controllers\Controller;
use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\AuthorizationLinkService;
use Illuminate\Http\Request;

class ConnectedToMollieController extends Controller
{
    /** @var AuthorizationLinkService */
    private $authorizeService;

    /** @var AuthenticatedUserLoader */
    private $userLoader;

    public function __construct(AuthorizationLinkService $authorizeService, AuthenticatedUserLoader $userLoader)
    {
        $this->authorizeService = $authorizeService;
        $this->userLoader = $userLoader;
    }

    public function __invoke(Request $request)
    {
        $user = $this->userLoader->load();

        return view('settings.payment.connect', [
            'authLink' => $this->authorizeService->getLink($user),
        ]);
    }
}
