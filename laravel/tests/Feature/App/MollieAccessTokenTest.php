<?php declare(strict_types=1);

namespace Tests\Feature\App;

use App\MollieAccessToken;
use DateTime;
use stdClass;
use Tests\TestCase;
use TypeError;

class MollieAccessTokenTest extends TestCase
{
    private const DATETIME = '2019-10-01 08:02:29';

    public function testWhenAssignExpiresAtWithInvalidValueThenThrowError(): void
    {
        $mollieAccessToken = new MollieAccessToken();

        $this->expectException(TypeError::class);

        $mollieAccessToken->expires_at = new stdClass();
    }

    public function testWhenGivenAValidValueToExpiresAtThenAssignToObject(): void
    {
        $mollieAccessToken = new MollieAccessToken();

        $mollieAccessToken->expires_at = new DateTime(self::DATETIME);

        $this->assertEquals(['expires_at' => self::DATETIME], $mollieAccessToken->toArray());
    }
}
