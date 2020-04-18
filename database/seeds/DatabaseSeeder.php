<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->userRolesTableSeeder();
        $this->call([UsersTableSeeder::class]);
    }

    /**
     * When online must move files to their on Seeder class.
     */

    protected function userRolesTableSeeder()
    {
        foreach(\App\Role::ROLES as $ROLE) {
            factory(\App\Role::create(['name' => $ROLE]));
        }
    }
}
