<?php

namespace App\Providers\Mollie;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;
use Mollie\OAuth2\Client\Provider\Mollie;

class OAuthClientServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(Mollie::class, function (): Mollie {
            return new Mollie([
                'clientId' => env('MOLLIE_CLIENT_ID'),
                'clientSecret' => env('MOLLIE_CLIENT_SECRET'),
                'redirectUri' => env('APP_URL') . '/settings/payment/oauth/return',
                'verify' => 'false',
            ], [
                'httpClient' => $this->app->get(HttpClient::class),
            ]);
        });
    }
}
