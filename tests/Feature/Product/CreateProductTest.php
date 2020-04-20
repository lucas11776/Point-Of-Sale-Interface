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
            $this->generateFakeImage()
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
            $this->generateFakeImage(), ['price' => 'num', 'in_stock' => 'num']
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
            $this->generateFakeImage('image.gif')
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
            $this->generateFakeImage('image.png', 4*1000)
        );

        $this->createProduct($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

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
            'name' => $name = ($faker = Faker::create())->sentence(4, 8),
            'slug' => Str::slug($name),
            'brand' => $faker->company,
            'in_stock' => rand(5,50),
            'price' => rand(20, 200),
            'discount' => rand(0, 150),
        ];
    }

    /**
     * Generate faker image.
     *
     * @param string $name
     * @param float $sizeKilobytes
     * @return array
     */
    protected function generateFakeImage(string $name = 'image.png', float $sizeKilobytes = 2*1000)
    {
        return [
            'image' => $file = UploadedFile::fake()->image($name)->size($sizeKilobytes)
        ];
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
