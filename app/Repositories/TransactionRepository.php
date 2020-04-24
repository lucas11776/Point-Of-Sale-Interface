<?php /** @noinspection ALL */


namespace App\Repositories;

use App\Sale;
use App\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as DbCollection;

class TransactionRepository implements TransactionInterface
{
    /**
     * @inheritDoc
     */
    public function checkout(array $transaction, array $saleables): Transaction
    {
        isset($transaction['price']) ?: $transaction = $this->saleablesSumAsPrice($saleables, $transaction);

        $transaction = $this->createTransaction($transaction);

        $this->insertTransactionSales($transaction, $saleables);

        return $transaction;
    }

    /**
     * @inheritDoc
     */
    public function update(Transaction $transaction, array $data = [], bool $sumSales = false): Transaction
    {
        // TODO: Implement update() method.
    }

    /**
     * Culculate the sum of all the sales.
     *
     * @param Collection $saleables
     * @return float
     */
    public function sum(Collection $saleables): float
    {
        return $saleables->sum(function($sale) {
            return $sale['price'] * $sale['quantity'];
        });
    }

    /**
     * @inheritDoc
     */
    public function delete(Transaction $transaction): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * Calculate saleables sum and merge the sum to array.
     *
     * @param array $array
     * @return array
     */
    protected function saleablesSumAsPrice(array $saleables, array $array = []): array
    {
        return array_merge($array, ['price' => $this->sum(collect($saleables))]);
    }

    /**
     * Create new transaction record in storage.
     *
     * @param array $data
     * @return Transaction
     */
    protected function createTransaction(array $data): Transaction
    {
        return Transaction::create([
            'customer_id' => $data['customer'] ?? null,
            'employee_id' => $data['employee'] ?? null,
            'device' => request()->ip(),
            'payed' => $data['payed'] ?? $data['price'],
            'price' => $data['price'],
        ]);
    }

    /**
     * Insert transaction sales in storage.
     *
     * @param Transaction $transaction
     * @param array $saleables
     * @return DbCollection
     */
    protected function insertTransactionSales(Transaction $transaction, array $saleables): bool
    {
        return Sale::insert(collect($saleables)->map(function($saleable) use($transaction) {
            return [
                'transaction_id' => $transaction->id,
                'saleable_id' => $saleable['id'],
                'saleable_type' => $saleable['type'],
                'price' => $saleable['price'],
                'quantity' => $saleable['quantity'],
            ];
        })->toArray());
    }
}
