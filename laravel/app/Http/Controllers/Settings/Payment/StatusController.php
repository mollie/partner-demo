<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Exceptions\UserNotConnectedToMollie;
use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\StatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class StatusController
{
    /** @var StatusService */
    private $service;

    /** @var AuthenticatedUserLoader */
    private $userLoader;

    public function __construct(StatusService $service, AuthenticatedUserLoader $userLoader)
    {
        $this->service = $service;
        $this->userLoader = $userLoader;
    }

    /**
     * @return RedirectResponse|View
     * @throws IdentityProviderException
     */
    public function __invoke()
    {
        $user = $this->userLoader->load();

        try {
            $status = $this->service->getOnboardingStatus($user);
        } catch (UserNotConnectedToMollie $e) {
            return redirect(route('connect_to_mollie'));
        }

        return view('settings.payment.status', [
            'status' => $status,
        ]);
    }
}
