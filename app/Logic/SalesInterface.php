<?php


namespace App\Logic;

use App\Transaction;
use Illuminate\Support\Collection;

interface SalesInterface
{
    /**
     * Create new sales.
     *
     * @param Transaction $transaction
     * @param Collection $sales
     * @return Collection
     */
    public function create(Transaction $transaction, Collection $sales): Collection;

    /**
     * Revert sales form transaction.
     *
     * @param Transaction $transaction
     * @param Collection $collection
     * @return bool
     */
    public function revert(Transaction $transaction, Collection $collection): bool;

    /**
     * Get total price of sales.
     *
     * @param Collection $sales
     * @return float
     */
    public function sum(Collection $sales): float;
}
