<?php declare(strict_types=1);

namespace Tests\Unit\App\Http\Middleware\Mollie;

use App\Http\Middleware\Mollie\ConnectedToMollieMiddleware;
use App\MollieAccessToken;
use App\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ConnectedToMollieMiddlewareTest extends TestCase
{
    private const ACCESS_TOKEN = 'access_abc123';
    private const REFRESH_TOKEN = 'refresh_abc123';

    use RefreshDatabase;

    /** @var ConnectedToMollieMiddleware */
    private $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = $this->app->get(ConnectedToMollieMiddleware::class);
    }

    public function testWhenUserIsNotConnectedToMollieThenRedirectToConnectPage(): void
    {
        Auth::setUser(new User());

        $response = $this->middleware->handle(new Request(), function (): Response {
            return new Response();
        });

        $this->assertTrue($response->isRedirect(route('connect_to_mollie')));
    }

    public function testWhenUserIsConnectedToMollieThenContinue(): void
    {
        Auth::setUser(User::find(1));
        MollieAccessToken::create([
            'user_id' => 1,
            'access_token' => self::ACCESS_TOKEN,
            'refresh_token' => self::REFRESH_TOKEN,
            'expires_at' => new DateTime('1970-01-01 10:10:58'),
        ]);

        $response = $this->middleware->handle(new Request(), function (): Response {
            return new Response('Continued');
        });

        $this->assertEquals(new Response('Continued'), $response);
    }
}
