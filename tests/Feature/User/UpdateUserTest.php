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
