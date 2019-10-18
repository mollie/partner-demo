<?php declare(strict_types=1);

namespace App\Exceptions;

use App\User;
use Exception;

class UserAlreadyConnectedToMollie extends Exception
{
    public function __construct(User $user)
    {
        parent::__construct(sprintf('User #%s is already connected to Mollie', $user->id));
    }
}
