<?php

namespace Tests\Feature\Product;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestResponse;

class CreateProductCategoryTest extends TestCase
{
    /**
     * Try to create category.
     * @param array $overideData
     */
    public function testCreateCategory($overideData = [])
    {
        auth()->login($this->getAdministrator());

        $data = array_merge(['name' => Faker::create()->jobTitle], $overideData);

        $this->createCategory($data)
            ->assertOk();
    }

    public function testCreateCategoryWithExistingCategory()
    {
        auth()->login($this->getAdministrator());

        $data = ['name' => Faker::create()->jobTitle];

        $this->testCreateCategory($data);

        $this->createCategory($data)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Try to create category with empty data.
     */
    public function testCreateCategoryWithEmptyData()
    {
        auth()->login($this->getAdministrator());

        $this->createCategory()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Make create category request to application.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function createCategory(array $data = []): TestResponse
    {
        return $this->json('POST', 'api/products/categories/create', $data);
    }
}
