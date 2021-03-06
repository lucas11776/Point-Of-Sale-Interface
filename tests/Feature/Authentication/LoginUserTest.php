<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;

class LoginUserTest extends TestCase
{
    /**
     * RegisterRequest factory password.
     */
    protected const USER_PASSWORD = 'password';

    /**
     * Try to login with valid credentials
     */
    public function testLoginWithValidCredentials()
    {
        $credentials = [
            'email' => ($user = factory(User::class)->create())->email,
            'password' => self::USER_PASSWORD
        ];

        $this->login($credentials)
            ->assertOk();
    }

    /**
     * Try to login when the user is authenticated.
     */
    public function testLoginAsAuthenticatedUser()
    {
        $credentials = [
            'email' => ($user = factory(User::class)->create())->email,
            'password' => self::USER_PASSWORD
        ];
        $token = auth()->attempt($credentials);

        $this->login($credentials, $token)
            ->assertUnauthorized();
    }

    /**
    * Try to login with no credentials.
    */
    public function testLoginWithEmptyCredentials(): void
    {
        $this->login([])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /**
     * Try to login with Invalid email address.
     */
    public function testLoginWithInvalidEmail()
    {
        $user = factory(User::class)->create();

        $this->login(['email' => str_replace('@', '', $user->email), 'password' => self::USER_PASSWORD])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Tring to login with password that is short.
     */
    public function testLoginWithShortPassword(): void
    {
        $user = factory(User::class)->create();

        $this->login(['email' => $user->email, 'password' => 'TEST'])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Trying to login with password that is longer that limit.
     */
    public function testLoginWithLongPassword(): void
    {
        $faker = Faker::create();
        $user = factory(User::class)->create();

        $this->login(['email' => $user->email, 'password' => $faker->paragraph(50),])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
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
