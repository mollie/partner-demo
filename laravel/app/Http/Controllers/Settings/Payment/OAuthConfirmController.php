<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Services\Mollie\AuthorizationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OAuthConfirmController
{
    /** @var AuthorizationCodeService */
    private $initializationService;

    public function __construct(AuthorizationCodeService $initializationService)
    {
        $this->initializationService = $initializationService;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $this->initializationService->authorize($request->get('code'), $user);

        return redirect(route('payment_status'));
    }
}
