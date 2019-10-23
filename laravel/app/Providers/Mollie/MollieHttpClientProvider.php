<?php declare(strict_types=1);

namespace App\Providers\Mollie;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class MollieHttpClientProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(HttpClient::class, function (): HttpClient {
            return new HttpClient();
        });
    }
}
