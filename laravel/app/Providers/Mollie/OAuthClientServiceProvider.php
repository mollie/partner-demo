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
            return new Mollie([
                'clientId' => env('MOLLIE_CLIENT_ID'),
                'clientSecret' => env('MOLLIE_CLIENT_SECRET'),
                'redirectUri' => 'http://localhost/settings/payment/oauth/confirm',
                'verify' => 'false',
            ]);
        });
    }
}


// http://localhost/settings/payment?code=auth_QNF3eE6BjxFqAufmCCpQVmAqbTaatH&state=78c4a218a9d73cec82b242b808d830e9
