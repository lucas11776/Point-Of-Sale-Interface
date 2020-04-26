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
            return Sale::create($this->formatTransactionAndSale($transaction, (array) $sale));
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
     * Format the transaction and sale to a single entity.
     *
     * @param Transaction $transaction
     * @param array $sale
     * @return array
     */
    private function formatTransactionAndSale(Transaction $transaction, array $sale): array
    {
        $sale['transaction_id'] = $transaction->id;
        $sale['saleable_id'] = $sale['id'];
        $sale['saleable_type'] = $sale['type'];

        return $sale;
    }
}
