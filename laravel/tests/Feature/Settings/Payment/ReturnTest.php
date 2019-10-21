<?php declare(strict_types=1);

namespace Tests\Feature\Settings\Payment;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReturnTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Auth::setUser(User::find(1));
    }

    public function testWhenReturnFromMollieHasCodeThenRedirectToConfirmEndpoint(): void
    {
        $code = 'auth_123abc';

        $response = $this->json('GET', route('return_from_mollie'), ['code' => $code]);

        $response->assertRedirect(url(route('oauth_confirm', $code)));
    }

    public function testWhenReturnFromMollieHasErrorThenRedirectToErrorEndpoint(): void
    {
        $error = 'access_denied';

        $response = $this->json('GET', route('return_from_mollie'), ['error' => $error]);

        $response->assertRedirect(url(route('oauth_error', $error)));
    }

    public function testWhenReturnFromMollieHasNotErrorAndNoCodeThenRedirectToHome(): void
    {
        $queryString = [];

        $response = $this->json('GET', route('return_from_mollie'), $queryString);

        $response->assertRedirect(url(route('payment_status')));
    }
}
