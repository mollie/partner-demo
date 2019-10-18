<?php declare(strict_types=1);

namespace App;

function time(): int
{
    return strtotime('2019-01-01 10:10:10');
}

namespace Tests\Feature\App;

use App\MollieAccessToken;
use DateTime;
use Tests\TestCase;

class MollieAccessTokenTest extends TestCase
{
    public function testWhenExpiresAtHasADateBeforeTodayThenIsExpiredIsTrue(): void
    {
        $expiresAt = new DateTime('2018-12-31 08:02:29');

        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);

        $this->assertTrue($accessToken->isExpired());
    }

    public function testWhenExpiresAtHasADateAfterTodayThenIsExpiredIsFalse(): void
    {
        $expiresAt = new DateTime('2019-02-01 08:02:29');

        $accessToken = new MollieAccessToken(['expires_at' => $expiresAt]);

        $this->assertFalse($accessToken->isExpired());
    }
}
