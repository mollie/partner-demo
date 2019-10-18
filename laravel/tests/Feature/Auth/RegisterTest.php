<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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

    /**
     * @dataProvider dpValidationErrors()
     * @param string[] $postData
     * @param string[] $errors
     */
    public function testWhenPostedDataIsInvalidThenReturnErrorMessage(array $postData, array $errors)
    {
        $response = $this->json('POST', '/register', $postData);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ]);
    }

    public function dpValidationErrors(): array
    {
        return [
            'empty company name' => [
                'postData' => array_merge($this->postData, ['company_name' => '']),
                'expected' => ['company_name' => ['The company name field is required.']],
            ],
            'empty website' => [
                'postData' => array_merge($this->postData, ['website' => '']),
                'expected' => ['website' => ['The website field is required.']],
            ],
            'invalid website' => [
                'postData' => array_merge($this->postData, ['website' => 'anything']),
                'expected' => ['website' => ['The website format is invalid.']],
            ],
            'empty email address' => [
                'postData' => array_merge($this->postData, ['email' => '']),
                'expected' => ['email' => ['The email field is required.']],
            ],
            'invalid email address' => [
                'postData' => array_merge($this->postData, ['email' => 'something else']),
                'expected' => ['email' => ['The email must be a valid email address.']],
            ],
            'empty password' => [
                'postData' => array_merge($this->postData, ['password' => '']),
                'expected' => ['password' => ['The password field is required.']],
            ],
            'short password' => [
                'postData' => array_merge($this->postData, ['password' => '12', 'password_confirmation' => '12']),
                'expected' => ['password' => ['The password must be at least 3 characters.']],
            ],
            'empty password_confirmation' => [
                'postData' => array_merge($this->postData, ['password_confirmation' => '']),
                'expected' => ['password' => ['The password confirmation does not match.']],
            ],
        ];
    }

    public function testWhenPostedDataIsValidThenRegisterUser(): void
    {
        $this->post('/register', $this->postData);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'company_name' => 'Mollie B.V',
            'website' => 'https://www.mollie.com/en/',
            'email' => 'info@mollie.com',
        ]);
    }
}
