<?php

/** @var Factory $factory */

use App\User;
use App\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'cellphone_number' => $faker->phoneNumber,
        'password' => Hash::make('password'), // password
    ];
})->afterCreating(User::class, function(User $user, Faker $faker) {
    return $user->image()->create([
        'path' => $faker->imageUrl(),
        'url' => $faker->imageUrl()
    ]);
});
