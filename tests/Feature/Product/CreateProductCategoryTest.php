<?php

namespace Tests\Feature\Product;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestResponse;

class CreateProductCategoryTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    function setUp(): void
    {
        parent::setUp();

        auth()->login($this->getAdministrator());
    }

    /**
     * Try to create category.
     *
     * @param array $paramData
     */
    public function testCreateCategory($paramData = [])
    {
        $data = array_merge(['name' => Faker::create()->jobTitle], $paramData);

        $this->createCategory($data)
            ->assertOk();
    }

    /**
     * Try to create category with empty data.
     */
    public function testCreateCategoryWithEmptyData()
    {
        $this->createCategory()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Try to create an existing category.
     */
    public function testCreateCategoryWithExistingName()
    {
        $data = ['name' => Faker::create()->jobTitle];

        $this->testCreateCategory($data);

        $this->createCategory($data)
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
