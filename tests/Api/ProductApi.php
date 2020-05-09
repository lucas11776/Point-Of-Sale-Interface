<?php

namespace Tests\Api;

use App\SubCategory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

trait ProductApi
{
    /**
     * Generate faker product
     *
     * @return array
     */
    protected function generateProduct(): array
    {
        return [
            'category_id' => ($subCategory = factory(SubCategory::class)->create())->category->id,
            'sub_category_id' => $subCategory->id,
            'name' => $name = ($faker = Faker::create())->unique(true, 10000)->name,
            'slug' => Str::slug($name),
            'in_stock' => rand(5,50),
            'brand' => $faker->company,
            'price' => rand(20, 200),
            'discount' => rand(0, 150),
            'quantity' => rand(0, 10),
        ];
    }
}
