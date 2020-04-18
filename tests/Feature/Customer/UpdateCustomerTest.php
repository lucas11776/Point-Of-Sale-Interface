<?php /** @noinspection ALL */

namespace Tests\Feature\Customer;

use App\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    /**
     * Try to update customer account.
     */
    public function testUpdateCustomer()
    {
        auth()->login($this->getEmployee());

        $customer = $this->getCustomer();

        $this->updateCustomer($customer->id, $customer->toArray())
            ->assertOk();
    }

    /**
     * Try to update user account with empty data.
     */
    public function testUpdateCustomerWithEmptyData()
    {
        auth()->login($this->getEmployee());

        $customer = $this->getCustomer();

        $this->updateCustomer($customer->id)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['first_name','last_name','email','cellphone_number']);
    }

    /**
     * Try to update customer with invalid email address.
     */
    public function testUpdateCustomerWithInvalidEmailAddress()
    {
        auth()->login($this->getEmployee());

        $customer = $this->getCustomer();
        $data = array_merge($customer->toArray(), ['email' => str_replace('@', '', $customer->email)]);

        $this->updateCustomer($customer->id, $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to update customer with short cellphone number phone number.
     */
    public function testUpdateCustomerWithShortPhoneNumber()
    {
        auth()->login($this->getEmployee());

        $customer = $this->getCustomer();
        $data = array_merge($customer->toArray(), ['cellphone_number' => '072 78']);

        $this->updateCustomer($customer->id, $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Try to update customer with short cellphone number phone number.
     */
    public function testUpdateCustomerWithLongPhoneNumber()
    {
        auth()->login($this->getEmployee());

        $customer = $this->getCustomer();
        $data = array_merge($customer->toArray(), ['cellphone_number' => '0727874859403758393564785']);

        $this->updateCustomer($customer->id, $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['cellphone_number']);
    }

    /**
     * Make update customer request to  application.
     *
     * @param int $customerId
     * @param array $data
     * @return TestResponse
     */
    protected function updateCustomer(int $customerId, array $data = []): TestResponse
    {
        return $this->json('PATCH', 'api/customers/' . $customerId . '/update', $data);
    }
}
