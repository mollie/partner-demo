<?php declare(strict_types=1);

namespace App;

class MollieStatus
{
    /** @var MollieAccessToken */
    private $accessToken;

    public function __construct(MollieAccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken(): MollieAccessToken
    {
        return $this->accessToken;
    }
}
