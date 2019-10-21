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
        } catch (UserDeniedAccessToMollie | Exception $e) {
            return redirect(url('home'))
                ->withErrors([
                    'message' => $e->getMessage(),
                ]);
        }

        return redirect(url('home'));
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

        throw new Exception('Unknown Error');
    }
}
