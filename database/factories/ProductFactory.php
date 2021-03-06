<?php

/** @var Factory $factory */

use App\Product;
use App\SubCategory;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'category_id' => ($subCategory = factory(SubCategory::class)->create())->category->id,
        'sub_category_id' => $subCategory->id,
        'name' => $name = $faker->unique(true, 100)->name,
        'slug' => Str::slug($name),
        'brand' => $faker->company,
        'in_stock' => rand(5,50),
        'price' => $price = rand(20, 200),
        'discount' => (rand(1, 10) % 2 == 0) ? rand(round($price % 1.5), $price) : null,
    ];
})->afterCreating(Product::class, function (Product $product, Faker $faker) {
    return $product->image()->create([
        'path' => '',
        'url' => $faker->imageUrl()
    ]);
});
