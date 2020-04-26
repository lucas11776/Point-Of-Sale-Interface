<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUndefinedMethodInspection */


namespace App\Logic;


use App\Sale;
use App\Transaction;
use Illuminate\Support\Collection;

class SalesLogic implements SalesInterface
{
    /**
     * @inheritDoc
     */
    public function create(Transaction $transaction, Collection $sales): Collection
    {
        return $sales->map(function($sale) use ($transaction) {
            return Sale::create($this->mergeTransactionIdWithSale($transaction, $sale));
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
     * Merge transaction and sales to single array
     *
     * @param Transaction $transaction
     * @param $sale
     * @return array
     */
    private function mergeTransactionIdWithSale(Transaction $transaction, $sale): array
    {
        return array_merge($sale = (array) $sale, [
            'transaction_id' => $transaction->id,
            'saleable_id' => $sale['id'],
            'saleable_type' => $sale['type']
        ]);
    }
}
