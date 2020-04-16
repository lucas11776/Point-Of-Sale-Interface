<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;

class ChangePasswordTest extends TestCase
{
    /**
     * Try to change user password.
     */
    public function testChangeUserPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'password' => $newPassword = Faker::create()->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertOk();
    }

    /**
     * Try to change password and login using new password.
     */
    /**
     * Try to change password and login login with old password after password has been change.
     */
    public function testChangePasswordAndLoginWithNewPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'password' => $newPassword = Faker::create()->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertOk();

        $credentials = ['email' => $user->email, 'password' => $newPassword];

        auth()->logout();

        $this->login($credentials)
            ->assertOk();
    }

    /**
     * Try to change password and login login with old password after password has been change.
     */
    public function testChangePasswordAndLoginWithOldPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'password' => $newPassword = Faker::create()->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertOk();

        $credentials = ['email' => $user->email, 'password' => $newPassword];

        $this->login($credentials)
            ->assertUnauthorized();
    }

    /**
     * Try to change password with empty old password.
     */
    public function testChangePasswordWithEmptyData()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['old_password','password']);
    }

    /**
     * Try to change password with long old password.
     */
    public function testChangePasswordWithInvalidOldPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => ($faker = Faker::create())->password(8,20),
            'new_password' => $newPassword = $faker->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['old_password']);
    }

    /**
     * Try to change password with short old password.
     */
    public function testChangePasswordWithShortOldPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => ($faker = Faker::create())->password(4,7),
            'new_password' => $newPassword = $faker->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['old_password']);
    }

    /**
     * Try to change password with long old password.
     */
    public function testChangePasswordWithLongOldPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => ($faker = Faker::create())->password(21,30),
            'new_password' => $newPassword = $faker->password(8,20),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['old_password']);
    }

    /**
     * Try to change password with short new password.
     */
    public function testChangePasswordWithShortNewPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'new_password' => $newPassword = Faker::create()->password(4,7),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Try to change password with long new password.
     */
    public function testChangePasswordWithShortLongPassword()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'new_password' => $newPassword = Faker::create()->password(21,35),
            'password_confirmation' => $newPassword
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Try to change password with invalid password confirmation.
     */
    public function testChangePasswordWithInvalidPasswordConfirmation()
    {
        $token = auth()->login($user = factory(User::class)->create());
        $data = [
            'old_password' => 'password',
            'new_password' => ($faker = Faker::create())->password(8,20),
            'password_confirmation' => $faker->password(8,20)
        ];

        $this->changePassword($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Make request to change user password.
     *
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    protected function changePassword(array $data, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('PATCH', 'api/user/change/password', $data);
    }

    /**
     * Make login request to application.
     *
     * @param array $credentials
     * @param string $token
     * @return TestResponse
     */
    protected function login(array $credentials = [], string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/authentication/login', $credentials);
    }
}
