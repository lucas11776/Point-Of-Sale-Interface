<?php


namespace Tests\Api;

use App\SubCategory;
use Faker\Factory as Faker;

trait ServiceApi
{
    /**
     * Generate new service data.
     *
     * @return array
     */
    public function generateService(): array
    {
        return [
            'category_id' => ($subCategory = factory(SubCategory::class)->create())->category->id,
            'sub_category_id' => $subCategory->id,
            'name' => ($faker = Faker::create())->unique()->name(),
            'brand' => $faker->unique()->company,
            'price' => $price = rand(2, 100)/2,
            'discount' => rand(1, 10) == 0 ? rand($price/2, $price) : null
        ];
    }
}
