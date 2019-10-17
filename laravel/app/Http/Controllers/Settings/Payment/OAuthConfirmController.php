<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Services\Mollie\AccessTokenInitializationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OAuthConfirmController
{
    /** @var AccessTokenInitializationService */
    private $initializationService;

    public function __construct(AccessTokenInitializationService $initializationService)
    {
        $this->initializationService = $initializationService;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $this->initializationService->initialize($request->get('code'));

        return redirect(route('payment_status'));
    }
}
