<?php declare(strict_types=1);

namespace App\Services\Mollie;

use Mollie\Api\MollieApiClient;

class AuthorizeService
{
    /** @var MollieApiClient */
    private $mollieClient;

    public function __construct(MollieApiClient $mollieClient)
    {
        $this->mollieClient = $mollieClient;
    }

    public function getAuthorizationLink(): string
    {
        dd(
            $this->mollieClient->profiles
        );

        return '';
    }
}
