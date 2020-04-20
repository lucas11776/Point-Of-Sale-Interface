<?php /** @noinspection ALL */

namespace Tests\Feature\Product;

use App\Product;
use App\Category;
use App\SubCategory;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;

class CreateProductSubCategoryTest extends TestCase
{
    /**
     * Try to create a new product sub category.
     */
    public function testCreateProductSubCategory()
    {
        auth()->login($this->getAdministrator());

        $category = $this->getProductCategory();
        $data = ['name' => Faker::create()->jobTitle];

        $this->createSubCategory($category->id, $data)
            ->assertOk();
    }

    /**
     * Try to create a new product sub category with empty data.
     */
    public function testCreateProductSubCategoryWithEmptyData()
    {
        auth()->login($this->getAdministrator());

        $category = $this->getProductCategory();

        $this->createSubCategory($category->id)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Get new product category.
     *
     * @return Category
     */
    protected function getProductCategory(): Category
    {
        return factory(Category::class)->create([
            'categorizable_type' => Product::class,
            'name' => $name = Faker::create()->jobTitle,
            'slug' => Str::slug($name)
        ]);
    }

    /**
     * Make request to create new sub category in the application.
     *
     * @param int $categoryId
     * @param array $data
     * @return TestResponse
     */
    protected function createSubCategory(int $categoryId, array $data = []): TestResponse
    {
        return $this->json('POST', 'api/products/categories/sub/' . $categoryId . '/create', $data);
    }
}
