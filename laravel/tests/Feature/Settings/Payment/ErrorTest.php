<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ErrorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));
    }

    public function testWhenResponseHasAccessDeniedErrorThenRedirectToHomeWithErrorMessage(): void
    {
        $errorType = 'access_denied';

        $response = $this->json('GET', sprintf('settings/payment/oauth/error/%s', $errorType));

        $response
            ->assertRedirect(url('home'))
            ->assertSessionHasErrors([
                'message' => 'User #1 denied access to Mollie',
            ]);
    }

    public function testWhenResponseHasUnknownErrorThenRedirectToHomeWithUnknownError(): void
    {
        $errorType = 'random_error';

        $response = $this->json('GET', sprintf('settings/payment/oauth/error/%s', $errorType));

        $response
            ->assertRedirect(url('home'))
            ->assertSessionHasErrors([
                'message' => 'Unknown Error',
            ]);
    }
}
