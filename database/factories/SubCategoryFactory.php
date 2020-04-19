<?php

/** @var Factory $factory */

use App\Category;
use App\SubCategory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(SubCategory::class, function (Faker $faker) {
    return [
        'category_id' => Category::query()->inRandomOrder()->first() ?? rand(1,20),
        'name' => $faker->jobTitle
    ];
});
