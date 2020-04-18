<?php

use App\User;
use App\Role;
use App\Customer;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->map(function(User $user, $index) {
            if($index % 2 == 0 AND $index != 0) {
                $this->createCustomerAccount($user);
            }
            if($index % 5 == 0 AND $index != 0) {
                $this->makeUserEmployee($user);
            }
            if($index % 9 == 0 AND $index != 0) {
                $this->makeUserAdministator($user);
            }
        });
    }

    /**
     * Create customer account using user account details.
     *
     * @param User $user
     */
    private function createCustomerAccount(User $user)
    {
        factory(Customer::class)->create($user->toArray());
    }

    /**
     * Make user a administator of the application.
     *
     * @param User $user
     */
    private function makeUserAdministator(User $user)
    {
        $user->addRole(Role::where('name', 'administrator')->first());
    }

    /**
     * Make user a employee of the application.
     *
     * @param User $user
     */
    private function makeUserEmployee(User $user)
    {
        $user->addRole(Role::where('name', 'administrator')->first());
    }
}
