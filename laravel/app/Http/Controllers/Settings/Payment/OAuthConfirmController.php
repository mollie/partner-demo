<?php declare(strict_types=1);

namespace App\Http\Controllers\Settings\Payment;

use App\Services\AuthenticatedUserLoader;
use App\Services\Mollie\AuthorizationCodeService;
use Illuminate\Http\RedirectResponse;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

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

    public function __invoke(string $authCode): RedirectResponse
    {
        $user = $this->userLoader->load();

        try {
            $this->initializationService->authorize($authCode, $user);
        } catch (IdentityProviderException $e) {
            return redirect(route('payment_status'))->withErrors([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);
        }

        return redirect(route('oauth_authorized_redirect'));
    }
}
