<?php

namespace Tests\Feature\Service;

use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateServiceTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        auth()->login($this->getEmployee());
    }

    /**
     * Try to create service in application.
     */
    public function testCreateService()
    {
        $data = array_merge(
            $this->generateImage(),
            $this->generateService()
        );

        $this->createService($data)
            ->assertOk()
            ->assertJsonStructure(['message']);
        Storage::disk('public')
            ->assertExists($data['image']->hashName());
    }

    /**
     * Try to create search with existing name.
     */
    public function testCreateServiceWithExstingName()
    {
        $service = factory(Service::class)->create();

        $this->createService($service->toArray())
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Try to create service with empty data.
     */
    public function testCreateServiceWithEmptyData()
    {
        $this->createService()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['category_id','sub_category_id','name','price']);
    }

    /**
     * Make request to create search in the application.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function createService(array $data = []): TestResponse
    {
        return $this->json('POST', 'api/services/create', $data);
    }
}
