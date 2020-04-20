<?php

/** @var Factory $factory */

use App\Product;
use App\Category;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Category::class, function (Faker $faker) {
    $categorizable = [Product::class];

    return [
        'categorizable_type' => $categorizable[rand(0, count($categorizable) - 1)],
        'name' => $name = $faker->unique()->jobTitle,
        'slug' => Str::slug($name)
    ];
});
