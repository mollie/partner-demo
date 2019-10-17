<?php declare(strict_types=1);

namespace App\Repositories;

use App\MollieAccessToken;
use App\User;

class MollieAccessTokenRepository
{
    public function create(MollieAccessToken $mollieAccessToken): void
    {
        $mollieAccessToken->save();
    }

    public function update(MollieAccessToken $accessToken): void
    {
        $accessToken->save();
    }

    public function getUserAccessToken(User $user): ?MollieAccessToken
    {
        return MollieAccessToken::where('user_id', $user->id)->first();
    }
}
