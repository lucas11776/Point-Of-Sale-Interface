<?php /** @noinspection ALL */

namespace Tests\Feature\Service;

use App\Category;
use App\Product;
use App\Service;
use App\SubCategory;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateServiceSubCategoryTest extends TestCase
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
     * Try to create a new product sub category.
     */
    public function testCreateServiceSubCategory()
    {
        $category = $this->getServiceCategory();
        $data = ['name' => Faker::create()->jobTitle];

        $this->createSubCategory($category->id, $data)
            ->assertOk();
    }

    /**
     * Try to create a new product sub category with empty data.
     */
    public function testCreateServiceSubCategoryWithEmptyData()
    {
        $category = $this->getServiceCategory();

        $this->createSubCategory($category->id)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Get new product category.
     *
     * @return Category
     */
    protected function getServiceCategory(): Category
    {
        return factory(Category::class)->create([
            'name' => $name = Faker::create()->unique()->jobTitle,
            'categorizable_type' => Service::class,
            'slug' => Str::slug($name),
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
        return $this->json('POST', 'api/services/categories/sub/' . $categoryId . '/create', $data);
    }
}
