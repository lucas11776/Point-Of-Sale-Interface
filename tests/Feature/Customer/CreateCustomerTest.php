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
     * Try to create customer with valid data.
     */
    public function testCreateCustomerWithValidData()
    {
        auth()->login($this->getAdministrator());
        $this->createCustomer($this->generateCustomer())
            ->assertOk();
    }

    /**
     * Try to create customer with valid data as normal user with no privallages.
     */
    public function testCreateCustomerWithValidDataAsUser()
    {
        auth()->login($this->getUser());
        $this->createCustomer($this->generateCustomer())
            ->assertUnauthorized();
    }

    /**
     * Try to create customer with empty data.
     */
    public function testCreateCustomerWithEmptyData()
    {
        auth()->login($this->getEmployee());
        $this->createCustomer([])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'cellphone_number']);
    }

    /**
     * Try to create customer with invalid email address.
     */
    public function testCreateCustomerWithInvalidEmail()
    {
        auth()->login($this->getEmployee());

        $customer = $this->generateCustomer();

        $customer['email'] = str_replace('@', '', $customer['email']);

        $this->createCustomer($customer)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to create a customer with existing email address.
     */
    public function testCreateCustomerWithExsitingEmailAddress()
    {
        auth()->login($this->getEmployee());

        $existingCustomer = factory(Customer::class)->create();
        $newCustomer = array_merge($this->generateCustomer(), ['email' => $existingCustomer->email]);

        $this->createCustomer($newCustomer)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to create customer with short phone number.
     */
    public function testCreateCustomerWithShortPhoneNumberAsEmployee()
    {
        auth()->login($this->getEmployee());

        $customer = $this->generateCustomer();

        $customer['cellphone_number'] = '072 95';

        $this->createCustomer($customer)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Try to create customer with long phone number.
     */
    public function testCreateCustomerWithLongPhoneNumberAsEmployee()
    {
        auth()->login($this->getEmployee());

        $customer = $this->generateCustomer();

        $customer['cellphone_number'] = $customer['cellphone_number'] . $customer['cellphone_number'];

        $this->createCustomer($customer)
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
     * @return TestResponse
     */
    protected function createCustomer(array $data): TestResponse
    {
        return $this->json('POST', 'api/customers/create', $data);
    }
}
