<?php

namespace Tests\Feature\Product;

use App\Product;
use Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\TestResponse;

class GetProductsTest extends TestCase
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
    public function testGetProducts()
    {
        $this->getProducts()
            ->assertOk();
    }

    /**
     * Try to search for a product in database.
     */
    public function testSearchProduct()
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
    public function testGetProductsByDate()
    {
        $data = ['end' => $this->products->get(10)->created_at,];

        $this->getProducts($data)
            ->assertOk();
    }

    /**
     * Try to get products by desc order.
     */
    public function testGetProductsAscOrder()
    {
        $this->getProducts(['order' => 'DESC'])
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
