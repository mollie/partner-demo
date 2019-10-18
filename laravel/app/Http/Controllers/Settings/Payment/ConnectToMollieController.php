<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Exceptions\UserAlreadyConnectedToMollie;
use App\Http\Controllers\Controller;
use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\AuthorizationLinkService;
use Illuminate\Http\Request;

class ConnectToMollieController extends Controller
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

        try {
            $authLink = $this->authorizeService->getLink($user);
        } catch (UserAlreadyConnectedToMollie $e) {
            return redirect(route('payment_status'));
        }

        return view('settings.payment.connect', [
            'authLink' => $authLink
        ]);
    }
}
