<?php declare(strict_types=1);

namespace App\Providers\Mollie;

use GuzzleHttp\Client as HttpClient;
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
            return new MollieApiClient(
                $this->app->get(HttpClient::class)
            );
        });
    }
}
