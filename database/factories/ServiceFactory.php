<?php

/** @var Factory $factory */

use App\Service;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Service::class, function (Faker $faker) {
    return [
        'name' => $name = $faker->unique(true)->name,
        'slug' => Str::slug($name),
        'brand' => $faker->companySuffix,
        'price' => rand(2, 100) / 2,
        'discount' => rand(1,10) % 2 == 0 ? rand(1, 100) : null
    ];
})->afterCreating(Service::class, function (Service $service, Faker $faker) {
    dd($service);
    return $service->image()->create([
        'path' => '',
        'url' => $faker->imageUrl()
    ]);
});
