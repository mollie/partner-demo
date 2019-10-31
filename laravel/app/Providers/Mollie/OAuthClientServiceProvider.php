<?php

namespace App\Providers\Mollie;

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
            $client = new Mollie([
                'clientId' => env('MOLLIE_CLIENT_ID'),
                'clientSecret' => env('MOLLIE_CLIENT_SECRET'),
                'redirectUri' => env('APP_URL').'/settings/payment/oauth/return',
                'verify' => 'false',
            ]);

            $apiUrl = env('MOLLIE_API_URL');
            $webUrl = env('MOLLIE_WEB_URL');

            if ($apiUrl) {
                $client->setMollieApiUrl($apiUrl);
            }

            if ($webUrl) {
                $client->setMollieWebUrl($webUrl);
            }

            return $client;
        });
    }
}
