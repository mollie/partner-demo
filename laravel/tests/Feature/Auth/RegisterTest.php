<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @var string[] */
    private $postData;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $password = uniqid();

        $this->postData = [
            'company_name' => 'Mollie B.V',
            'website' => 'https://www.mollie.com/en/',
            'email' => 'info@mollie.com',
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Session::start();
    }

    /**
     * @dataProvider dpValidationErrors()
     * @param string[] $postData
     * @param string[] $expected
     */
    public function testWhenPostedDataIsInvalidThenReturnErrorMessage(array $postData, array $expected)
    {
        $postData = array_merge($postData, ['_token' => csrf_token()]);

        $this->post('/register', $postData);

        $this->assertEquals($expected, session('errors')->all());
    }

    public function dpValidationErrors(): array
    {
        return [
            'empty company name' => [
                'postData' => array_merge($this->postData, ['company_name' => '']),
                'expected' => ['The company name field is required.'],
            ],
            'empty website' => [
                'postData' => array_merge($this->postData, ['website' => '']),
                'expected' => ['The website field is required.'],
            ],
            'invalid website' => [
                'postData' => array_merge($this->postData, ['website' => 'anything']),
                'expected' => ['The website format is invalid.'],
            ],
            'empty email address' => [
                'postData' => array_merge($this->postData, ['email' => '']),
                'expected' => ['The email field is required.'],
            ],
            'invalid email address' => [
                'postData' => array_merge($this->postData, ['email' => 'something else']),
                'expected' => ['The email must be a valid email address.'],
            ],
            'empty password' => [
                'postData' => array_merge($this->postData, ['password' => '']),
                'expected' => ['The password field is required.'],
            ],
            'short password' => [
                'postData' => array_merge($this->postData, ['password' => '123', 'password_confirmation' => '123']),
                'expected' => ['The password must be at least 8 characters.'],
            ],
            'empty password_confirmation' => [
                'postData' => array_merge($this->postData, ['password_confirmation' => '']),
                'expected' => ['The password confirmation does not match.'],
            ],
        ];
    }

    public function testWhenPostedDataIsValidThenRegisterUser(): void
    {
        $postData = array_merge($this->postData, ['_token' => csrf_token()]);

        $this->post('/register', $postData);

        /** @var User $user */
        $user = User::where('email', 'info@mollie.com')->first();
        $user->addHidden(['created_at', 'updated_at']);
        $this->assertEquals(
            [
                'id' => 1,
                'company_name' => 'Mollie B.V',
                'website' => 'https://www.mollie.com/en/',
                'email' => 'info@mollie.com',
                'email_verified_at' => null,
            ],
            $user->toArray()
        );
    }
}
