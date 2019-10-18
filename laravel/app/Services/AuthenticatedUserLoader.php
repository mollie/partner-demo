<?php declare(strict_types=1);

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserLoader
{
    public function load(): User
    {
        return Auth::user();
    }
}
