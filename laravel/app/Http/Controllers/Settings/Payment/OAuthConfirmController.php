<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\AuthorizationCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OAuthConfirmController
{
    /** @var AuthorizationCodeService */
    private $initializationService;

    /** @var AuthenticatedUserLoader */
    private $userLoader;

    public function __construct(AuthorizationCodeService $initializationService, AuthenticatedUserLoader $userLoader)
    {
        $this->initializationService = $initializationService;
        $this->userLoader = $userLoader;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $user = $this->userLoader->load();

        $this->initializationService->authorize($request->get('code'), $user);

        return redirect(route('payment_status'));
    }
}
