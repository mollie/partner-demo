<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Exceptions\UserNotConnectedToMollie;
use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\PaymentMethodService;
use App\Services\Mollie\StatusService;
use App\Services\Mollie\UserPaymentProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class StatusController
{
    /** @var StatusService */
    private $onboardingService;

    /** @var AuthenticatedUserLoader */
    private $userLoader;

    /** @var UserPaymentProfileService */
    private $profileService;

    /** @var PaymentMethodService */
    private $paymentMethodsService;

    public function __construct(
        StatusService $onboardingService,
        AuthenticatedUserLoader $userLoader,
        UserPaymentProfileService $profileService,
        PaymentMethodService $paymentMethodsService
    ) {
        $this->onboardingService = $onboardingService;
        $this->userLoader = $userLoader;
        $this->profileService = $profileService;
        $this->paymentMethodsService = $paymentMethodsService;
    }

    /**
     * @return RedirectResponse|View
     * @throws IdentityProviderException
     */
    public function __invoke()
    {
        $user = $this->userLoader->load();

        try {
            $status = $this->onboardingService->getOnboardingStatus($user);
        } catch (UserNotConnectedToMollie $e) {
            return redirect(route('connect_to_mollie'));
        }

        $profiles = $this->profileService->loadUserProfile($user);
        $methods = $this->paymentMethodsService->loadFromProfile($user, current($profiles));

        return view('settings.payment.status', [
            'status' => $status,
            'profiles' => $profiles,
            'methods' => $methods,
        ]);
    }
}
