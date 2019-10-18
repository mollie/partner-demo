<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Exceptions\UserDeniedAccessToMollie;
use App\Services\AuthenticatedUserLoader;

class OAuthErrorController
{
    private const MOLLIE_ACCESS_DENIED = 'access_denied';

    /**
     * @var AuthenticatedUserLoader
     */
    private $userLoader;

    public function __construct(AuthenticatedUserLoader $userLoader)
    {
        $this->userLoader = $userLoader;
    }

    /**
     * @throws UserDeniedAccessToMollie
     */
    public function __invoke(string $errorType)
    {
        if ($errorType === self::MOLLIE_ACCESS_DENIED) {
            throw new UserDeniedAccessToMollie($this->userLoader->load());
        }
    }
}