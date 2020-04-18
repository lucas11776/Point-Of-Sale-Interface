<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestResponse;

class UpdateUserTest extends TestCase
{
    /**
     * Try to update user account with new first name.
     */
    public function testUpdateUserFirstName()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['first_name' => Faker::create()->firstName]);

        $this->updateUser($data, $token)
            ->assertOk();
    }

    /**
     * Try to update user account with empty first name.
     */
    public function testUpdateUserWithEmptyFirstName()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['first_name' => '']);

        $this->updateUser($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Try to update user account with new last name.
     */
    public function testUpdateUserAccountLastName()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['last_name' => Faker::create()->lastName]);

        $this->updateUser($data, $token)
            ->assertOk();
    }

    /**
     * Try to update user account with last name.
     */
    public function testUpdateUserAccountWithEmptyLastName()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['last_name' => '']);

        $this->updateUser($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Try to update user account with new phone number.
     */
    public function testUpdateUserAccountPhoneNumber()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['cellphone_number' => Faker::create()->e164PhoneNumber]);

        $this->updateUser($data, $token)
            ->assertOk();
    }

    /**
     * Try to update user account with empty phone number.
     */
    public function testUpdateUserAccountWithEmptyPhoneNumber()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['cellphone_number' => '']);

        $this->updateUser($data, $token)
            ->assertOk();
    }

    /**
     * Try to update user account with short phone number phone number.
     */
    public function testUpdateUserAccountWithShortPhoneNumber()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['cellphone_number' => '07219']);

        $this->updateUser($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Try to update user account with short phone number phone number.
     */
    public function testUpdateUserAccountWithLongPhoneNumber()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), ['cellphone_number' => '07219743824623862359234923753278']);

        $this->updateUser($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Update user account.
     *
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    protected function updateUser(array $data = [], string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('PATCH', 'api/user/update', $data);
    }
}
