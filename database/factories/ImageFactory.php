<?php

/** @var Factory $factory */

use App\Image;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'imageable_id' => rand(1, 1000000),
        'imageable_type' => get_class($this),
        'path' => $faker->imageUrl(),
        'url' => $faker->imageUrl()
    ];
});
