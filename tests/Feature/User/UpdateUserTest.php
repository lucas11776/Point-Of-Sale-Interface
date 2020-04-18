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
    public function testUpdateUserAccount()
    {
        $token = auth()->login($user = User::first());
        $data = array_merge($user->toArray(), [
            'first_name' => ($faker = Faker::create())->firstName,
            'last_name' => $faker->lastName,

        ]);

        $this->updateUser($data, $token)
            ->assertOk();
    }

    /**
     * Try to update user account with last name.
     */
    public function testUpdateUserAccountWithEmptyData()
    {
        auth()->login($this->getUser());

        $this->updateUser([], '')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name','last_name']);
    }

    /**
     * Try to update user account with short phone number phone number.
     */
    public function testUpdateUserAccountWithShortPhoneNumber()
    {
        auth()->login($user = $this->getUser());

        $data = array_merge($user->toArray(), ['cellphone_number' => '07219']);

        $this->updateUser($data, '')
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Try to update user account with short phone number phone number.
     */
    public function testUpdateUserAccountWithLongPhoneNumber()
    {
        auth()->login($user = User::first());

        $data = array_merge($user->toArray(), ['cellphone_number' => '07219743824623862359234923753278']);

        $this->updateUser($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Update user account.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function updateUser(array $data = []): TestResponse
    {
        return $this->json('PATCH', 'api/user/update', $data);
    }
}
