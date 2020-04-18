<?php

namespace Tests;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

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
        return User::first();
    }

    /**
     * Get administrator account.
     *
     * @return User
     */
    protected function getAdministrator(): User
    {
        return Role::where('name', 'administrator')->first()->users()->first();
    }

    /**
     * Get employee account.
     *
     * @return User
     */
    protected function loginAsEmployee(): User
    {
        return Role::where('name', 'employee')->first()->users()->first();
    }
}
