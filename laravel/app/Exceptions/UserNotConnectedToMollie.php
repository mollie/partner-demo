<?php declare(strict_types=1);

namespace App\Exceptions;

use App\User;
use Exception;

class UserNotConnectedToMollie extends Exception
{
    public function __construct(User $user)
    {
        parent::__construct(sprintf('User #%s not connected to Mollie', $user->id));
    }
}
