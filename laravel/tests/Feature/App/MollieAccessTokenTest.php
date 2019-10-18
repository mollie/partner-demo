<?php declare(strict_types=1);

namespace Tests\Feature\App;

use App\MollieAccessToken;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MollieAccessTokenTest extends TestCase
{
    use RefreshDatabase;

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
