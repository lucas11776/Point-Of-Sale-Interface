<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;
use Faker\Factory as Faker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Try to register with valid data.
     */
    public function testRegisterWithVaildData()
    {
        $this->register($this->generateUser())
            ->assertOk();
    }

    /**
     * Try to register and try to login with the registered user
     */
    public function testRegisterAndLoginWithRegisteredUser()
    {
        $this->register($data = $this->generateUser())
            ->assertOk();

        auth()->logout();

        $this->login($data)
            ->assertOk();
    }

    /**
     * Try to register with exist email address.
     */
    public function testRegisterWithExistEmailAddress()
    {
        $user = factory(User::class)->create();
        $data = array_merge($this->generateUser(), ['email' => $user->email]);

        $this->register($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to register as authenticated user.
     */
    public function testRegisterAsAuthenticatedUser()
    {
        $token = auth()->login(factory(User::class)->create());
        $newUser = $this->generateUser();

        $this->register($newUser, $token)
            ->assertUnauthorized();
    }

    /**
     * Try to register empty empty data.
     */
    public function testRegisterWithEmptyData()
    {
        $this->register([])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'password']);
    }

    /**
     * Try to register with empty first name.
     */
    public function testRegisterWithEmptyFirstName()
    {
        $this->register($this->generateUser('first_name'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Try to register with empty first last name.
     */
    public function testRegisterWithEmptyLastName()
    {
        $this->register($this->generateUser('last_name'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Try to register with empty email address
     */
    public function testRegisterWithEmptyEmail()
    {
        $this->register($this->generateUser('email'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Try to register with invalid email address.
     */
    public function testRegisterWithInvalidEmail()
    {
        $newUser = $this->generateUser();
        $newUser['email'] = str_replace('@', '', $newUser['email']);

        $this->register($newUser)->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('email');
    }

    /**
     * Try to register with empty password.
     */
    public function testRegiterWithEmptyPassword()
    {
        $this->register($this->generateUser('password'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }

    /**
     * Try to register with empty password confirmation.
     */
    public function testRegisterWithEmptyPasswordConfirmation()
    {
        $this->register($this->generateUser($key = 'password_confirmation'))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('password');
    }

    /**
     * Try to register with password confirmation that does not match password.
     */
    public function testRegisterWithIncorrectPasswordConfirmation()
    {
        $newUser = $this->generateUser();

        $newUser['password_confirmation'] = $newUser['password_confirmation'] . '&^';

        $this->register($newUser)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Generate new register form data using faker.
     *
     * @param string|null $removeKey
     * @return array
     */
    protected function generateUser(string $removeKey = null): array
    {
        $newUser = [
            'first_name' =>  ($faker = Faker::create())->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->email,
            'password' => $password =  $faker->password(8,20),
            'password_confirmation' => $password
        ];

        return is_null($removeKey) ? $newUser : array_merge($newUser, [strtolower($removeKey) => '']);
    }

    /**
     * Makes register request to application.
     *
     * @param array $newUser
     * @param string $token
     * @return TestResponse
     */
    protected function register(array $newUser, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/authentication/register', $newUser);
    }

    /**
     * Makes login request to application.
     *
     * @param array $credentials
     * @param string $token
     * @return TestResponse
     */
    protected function login(array $credentials, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/authentication/login', $credentials);
    }
}
