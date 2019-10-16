<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mollie\Api\MollieApiClient;

class MollieApiClientServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(MollieApiClient::class, function () {
            $client = new MollieApiClient();
            $client->setApiKey(env('MOLLIE_KEY'));

            return $client;
        });
    }
}
