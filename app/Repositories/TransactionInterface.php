<?php


namespace App\Repositories;

use App\Transaction;

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
     * Calculate sales total sum.
     *
     * @param \Illuminate\Support\Collection $sales
     * @return float
     */
    public function sum(\Illuminate\Support\Collection $sales): float;

    /**
     * Delete a transaction from storate
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function delete(Transaction $transaction): bool;
}
