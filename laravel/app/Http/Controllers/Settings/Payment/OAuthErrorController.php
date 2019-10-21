<?php

namespace App\Http\Controllers\Settings\Payment;

use App\Exceptions\UserDeniedAccessToMollie;
use App\Services\AuthenticatedUserLoader;
use Exception;

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
     * @throws Exception
     */
    public function __invoke(string $errorType)
    {
        try {
            $this->parserError($errorType);
        } catch (UserDeniedAccessToMollie $e) {
            return redirect(url('home'))
                ->withErrors([
                    'message' => $e->getMessage(),
                ]);
        }

        throw new Exception('Unknown Error');
    }

    /**
     * @throws UserDeniedAccessToMollie
     * @throws Exception
     */
    private function parserError(string $errorType)
    {
        if ($errorType === self::MOLLIE_ACCESS_DENIED) {
            throw new UserDeniedAccessToMollie($this->userLoader->load());
        }
    }
}
