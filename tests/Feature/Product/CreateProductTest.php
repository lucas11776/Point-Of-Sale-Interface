<?php

namespace Tests\Feature\Product;

use App\SubCategory;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateProductTest extends TestCase
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
     * Try to create new product in store.
     */
    public function testCreateProduct()
    {
        $data = array_merge(
            $this->generateProduct(),
            $this->generateImage()
        );

        $this->createProduct($data)
            ->assertOk();
        Storage::disk('public')
            ->assertExists($data['image']->hashName());
    }

    /**
     * Try to create product with empty data.
     */
    public function testCreateProductWithEmptyData()
    {
        $this->createProduct([])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['category_id', 'sub_category_id', 'name', 'in_stock', 'price']);
    }

    /**
     * Try to create new product with invalid price and in_stock in store.
     */
    public function testCreateProductWithInvalidPriceAndInstock()
    {
        $data = array_merge(
            $this->generateProduct(),
            $this->generateImage(), ['price' => 'num', 'in_stock' => 'num']
        );

        $this->createProduct($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['price', 'in_stock']);
    }

    /**
     * Try to create new product with invalid extension in store.
     */
    public function testCreateProductWithInvalidImageExtension()
    {
        $data = array_merge(
            $this->generateProduct(),
            $this->generateImage('image.gif')
        );

        $this->createProduct($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Try to create new product with invalid image size in store.
     */
    public function testCreateProductWithMaxOutFileSize()
    {
        $data = array_merge(
            $this->generateProduct(),
            $this->generateImage('image.png', 4*1000)
        );

        $this->createProduct($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Upload or create new product.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function createProduct(array $data): TestResponse
    {
        return $this->json('POST', 'api/products/create', $data);
    }
}
