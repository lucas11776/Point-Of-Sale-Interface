<?php

/** @var Factory $factory */

use App\Category;
use App\SubCategory;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(SubCategory::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class)->create()->id,
        'name' => $name = $faker->unique()->jobTitle,
        'slug' => Str::slug($name)
    ];
});
