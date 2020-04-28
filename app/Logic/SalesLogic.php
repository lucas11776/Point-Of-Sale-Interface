<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUndefinedMethodInspection */


namespace App\Logic;


use App\Product;
use App\Sale;
use App\Transaction;
use Illuminate\Support\Collection;

class SalesLogic implements SalesInterface
{
    /**
     * @inheritDoc
     */
    public function store(Transaction $transaction, Collection $sales): Collection
    {
        return $sales->map(function($sale) use($transaction) {
            return $this->create($transaction, (array) $sale);
        });
    }

    /**
     * @inheritDoc
     */
    public function revert(Transaction $transaction, Collection $collection): bool
    {
        // TODO: Implement revert() method.
    }

    /**
     * @inheritDoc
     */
    public function sum(Collection $sales): float
    {
        return (float) $sales->sum(function($item) {
            return ($item = (object) $item)->price * $item->quantity;
        });
    }

    /**
     * @inheritDoc
     */
    public function delete(Sale $sale): bool
    {

    }

    /**
     * Create new sale in database.
     *
     * @param Transaction $transaction
     * @param array $sale
     * @return Transaction
     */
    protected function create(Transaction $transaction, array $sale): Sale
    {
        $sale['type'] != Product::class ?: $this->subProductInStock(Product::find($sale['id']), $sale['quantity']);

        return Sale::create([
            'price' => $sale['price'],
            'saleable_id' => $sale['id'],
            'quantity' => $sale['quantity'],
            'saleable_type' => $sale['type'],
            'transaction_id' => $transaction->id,
        ]);
    }

    /**
     * Decrease the number of products in stock.
     *
     * @param Product $product
     * @param int $sub
     * @return bool
     */
    protected function subProductInStock(Product $product, int $sub): bool
    {
        return $product->update([
            'in_stock' => $sub >= $product->in_stock ? 0 : $product->in_stock - $sub
        ]);
    }

    /**
     * Increase the number of products in stock.
     *
     * @param Product $product
     * @param int $add
     * @return bool
     */
    protected function addProductQuantity(Product $product, int $add): bool
    {
        return $product->update([
            'in_stock' => $product->in_stock + $add
        ]);
    }

    /**
     * Format the transaction and sale to a single entity.
     *
     * @param Transaction $transaction
     * @param array $sale
     * @return array
     */
    protected function formatTransactionAndSale(Transaction $transaction, array $sale): array
    {
        $sale['transaction_id'] = $transaction->id;
        $sale['saleable_id'] = $sale['id'];
        $sale['saleable_type'] = $sale['type'];

        return $sale;
    }
}
