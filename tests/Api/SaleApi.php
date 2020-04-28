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

    /**
     * Covert product to sales.
     *
     * @param object $item
     * @return array
     */
    protected function itemToSale(object $item): array
    {
        return [
            'id' => $item->id,
            'type' => get_class($item),
            'quantity' => $quantity = rand(1, 10),
            'price' => (float) ($item->discount ?? $item->price) * $quantity,
        ];
    }
}
