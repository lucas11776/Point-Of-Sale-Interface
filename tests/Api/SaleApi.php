<?php


namespace Tests\Api;


use App\Product;
use Illuminate\Support\Collection;

trait SaleApi
{
    /**
     * Generate sales for a transaction.
     *
     * @return Collection
     */
    protected function generateSales()
    {
        return collect()
            ->merge(factory(Product::class)->times(rand(1,10))->create())
            ->map(function(object $item) {
                return $this->itemToSale($item);
            });
    }
}
