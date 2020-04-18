<?php /** @noinspection ALL */

namespace Tests;

use App\Customer;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /**
     * Get regular user account.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return factory(User::class)->create();
    }

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

    /**
     * Get a random administrator account.
     *
     * @return User
     */
    protected function getAdministrator(): User
    {
        return $this->getUserByRole('administrator');
    }

    /**
     * Get a random employee account.
     *
     * @return User
     */
    protected function getEmployee(): User
    {
        return $this->getUserByRole('employee');
    }

    /**
     * Create new user with the given role.
     *
     * @param string $role
     * @return User
     */
    private function getUserByRole(string $role): User
    {
        $role = Role::where('name', $role)->first();
        $user = factory(User::class)->create();

        $user->addRole($role);

        return $user;
    }
}
