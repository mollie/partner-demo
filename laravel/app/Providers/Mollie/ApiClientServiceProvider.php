<?php declare(strict_types=1);

namespace App\Providers\Mollie;

use Illuminate\Support\ServiceProvider;
use Mollie\Api\MollieApiClient;

class ApiClientServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(MollieApiClient::class, function (): MollieApiClient {
            $client = new MollieApiClient();
            $apiUrl = env('MOLLIE_API_URL');

            if ($apiUrl) {
                $client->setApiEndpoint($apiUrl);
            }

            return $client;
        });
    }
}
