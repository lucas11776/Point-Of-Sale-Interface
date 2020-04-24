<?php

namespace Tests\Feature\Service;

use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;

class CreateServiceCategoryTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        auth()->login($this->getAdministrator());
    }

    /**
     * Try to create service category.
     * @param array $paramData
     */
    public function testCreateServiceCategory(array $paramData = [])
    {
        $data = array_merge(['name' => Faker::create()->jobTitle], $paramData);

        $this->createServiceCategory($data)
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * Try to create service category.
     */
    public function testCreateServiceCategoryWithEmptyData()
    {
        $this->createServiceCategory()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Try to create service category.
     */
    public function testCreateServiceCategoryWithExstingName()
    {
        $this->createServiceCategory()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Make request to create service category in application.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function createServiceCategory(array $data = []): TestResponse
    {
        return $this->json('POST', 'api/services/categories/create', $data);
    }
}
