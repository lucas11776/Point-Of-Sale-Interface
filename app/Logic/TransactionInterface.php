<?php


namespace App\Logic;

use App\Transaction;
use Illuminate\Support\Collection;

interface TransactionInterface
{
    /**
     * Create new transaction and insert transaction sales.
     *
     * @param array $transaction
     * @param array $sales
     * @return Transaction
     */
    public function checkout(array $transaction, array $sales): Transaction;

    /**
     * Update transaction sum and total sales price.
     *
     * @param Transaction $transaction
     * @return Transaction
     */
    public function update(Transaction $transaction): Transaction;

    /**
     * Delete a transaction from storage.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function delete(Transaction $transaction): bool;
}
