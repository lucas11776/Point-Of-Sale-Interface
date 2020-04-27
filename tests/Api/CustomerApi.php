<?php


namespace Tests\Api;


use App\Customer;
use App\User;

trait CustomerApi
{
    /**
     * Get a random customer account.
     *
     * @return Customer
     */
    protected function getCustomer(): Customer
    {
        $user = factory(User::class)->create();

        return factory(Customer::class)->create($user->toArray());
    }
}
