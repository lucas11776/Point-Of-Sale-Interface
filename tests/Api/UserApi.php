<?php


namespace Tests\Api;

use App\Role;
use App\User;

trait UserApi
{
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
