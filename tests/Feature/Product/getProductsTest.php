<?php

namespace Tests\Feature\Product;

use App\Product;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Support\Collection;

class getProductsTest extends TestCase
{
    /**
     * @var Collection
     */
    private $products;


    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->products = factory(Product::class)->times(20)->create();
    }

    /**
     * Try to get products in storage.
     *
     * @return void
     */
    public function _testGetProducts()
    {
        $this->getProducts()
            ->assertOk();
    }

    /**
     * Try to search for a product in database.
     */
    public function _testSearchProduct()
    {
        $data = [
            'search' => $this->products->get(0)->name
        ];

        $response = $this->getProducts($data)
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->assertTrue(
            $response->json('data')[0]['name'] == $data['search'],
            'Did not get product we were searching..'
        );
    }

    /**
     * Try to search for a product in database.
     */
    public function _testGetProductsByDate()
    {
        $data = [
            'start' =>  $this->products->get(4)->created_at,
            'end' => $this->products->get(9)->created_at,
        ];

        $this->getProducts($data)
            ->assertOk()
            ->assertJsonCount(5, 'data');
    }

    /**
     *
     *
     * @return void
     */
    public function _testGetProductsAscOrder()
    {
        $this->getProducts(['order' => 'ASC'])
            ->assertOk();
    }

    /**
     * Make request to get products in database.
     *
     * @param array $params
     * @return TestResponse
     */
    protected function getProducts(array $params = []): TestResponse
    {
        return $this->json('GET', 'api/products', $params);
    }
}
