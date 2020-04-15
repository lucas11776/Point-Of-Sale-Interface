<?php

namespace Tests\Feature;

use App\Customer;
use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use Faker\Factory as Faker;
use Tests\TestCase;

class CreateCustomerTest extends TestCase
{
    /**
     * Try to create customer with valid data as administrator.
     */
    public function testCreateCustomerWithValidDataAsAdministrator()
    {
        $user = factory(User::class)->create();

        $user->roles()->create(['name' => 'administrator']);

        $this->createCustomer($this->generateCustomer(), auth()->login($user))
            ->assertOk();
    }

    /**
     * Try to create customer with valid data as employee.
     */
    public function testCreateCustomerWithValidDataAsEmployee()
    {
        $user = factory(User::class)->create();

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($this->generateCustomer(), auth()->login($user))
            ->assertOk();
    }

    /**
     * Try to create customer with valid data as normal user with no privallages.
     */
    public function testCreateCustomerWithValidDataAsUser()
    {
        $user = factory(User::class)->create();

        $this->createCustomer($this->generateCustomer(), auth()->login($user))
            ->assertUnauthorized();
    }

    /**
     * Try to create customer with empty data.
     */
    public function testCreateCustomerWithEmptyDataAsEmployee()
    {
        $user = factory(User::class)->create();

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer([], auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'cellphone_number']);
    }

    /**
     * Try to create customer with empty first name.
     */
    public function testCreateCustomerWithEmptyFirstNameAsEmployee()
    {
        $user = factory(User::class)->create();
        $newCustomer = array_merge($this->generateCustomer(), ['first_name' => '']);

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Try to create customer with empty lastname.
     */
    public function testCreateCustomerWithEmptyLastnameAsEmployee()
    {
        $user = factory(User::class)->create();
        $newCustomer = array_merge($this->generateCustomer(), ['last_name' => '']);

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Try to create customer with empty email address.
     */
    public function testCreateCustomerWithEmptyEmailAddressAsEmployee()
    {
        $createdUser = factory(User::class)->create();
        $generatedCustomer = array_merge($this->generateCustomer(), ['email' => '']);

        $createdUser->roles()->create(['name' => 'employee']);

        $this->createCustomer($generatedCustomer, auth()->login($createdUser))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to create customer with invalid email address.
     */
    public function testCreateCustomerWithInvalidEmailAsEmployee()
    {
        $user = factory(User::class)->create();
        $newCustomer = $this->generateCustomer();

        $newCustomer['email'] = str_replace('@', '', $newCustomer['email']);

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to create a customer with existing email address.
     */
    public function testCreateCustomerWithExsitingEmailAddressAsEmployee()
    {
        $user = factory(User::class)->create();
        $customer = factory(Customer::class)->create();
        $newCustomer = array_merge($this->generateCustomer(), ['email' => $customer->email]);

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to create customer with short phone number.
     */
    public function testCreateCustomerWithShortPhoneNumberAsEmployee()
    {
        $user = factory(User::class)->create();
        $newCustomer = $this->generateCustomer();

        $newCustomer['cellphone_number'] = '072 95';

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Try to create customer with long phone number.
     */
    public function testCreateCustomerWithLongPhoneNumberAsEmployee()
    {
        $user = factory(User::class)->create();
        $newCustomer = $this->generateCustomer();

        $newCustomer['cellphone_number'] = $newCustomer['cellphone_number'] . $newCustomer['cellphone_number'];

        $user->roles()->create(['name' => 'employee']);

        $this->createCustomer($newCustomer, auth()->login($user))
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Get customer for data generated using faker.
     *
     * @return array
     */
    protected function generateCustomer(): array
    {
        return [
            'first_name' => ($faker = Faker::create())->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->email,
            'cellphone_number' => $faker->e164PhoneNumber
        ];
    }

    /**
     * Create new customer account.
     *
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    protected function createCustomer(array $data, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/customers/create', $data);
    }
}
