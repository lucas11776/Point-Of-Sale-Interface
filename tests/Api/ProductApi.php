<?php


namespace Tests\Api;


use App\Product;
use App\SubCategory;
use Faker\Factory as Faker;
use Illuminate\Support\Collection;
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
            'name' => $name = ($faker = Faker::create())->unique(true, 10000)->sentence(8, 20),
            'slug' => Str::slug($name),
            'brand' => $faker->company,
            'in_stock' => rand(5,50),
            'price' => rand(20, 200),
            'discount' => rand(0, 150),
            'quantity' => rand(0, 10),
        ];
    }
}
