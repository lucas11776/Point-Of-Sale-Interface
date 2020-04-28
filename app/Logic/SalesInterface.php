<?php


namespace App\Logic;

use App\Sale;
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
    public function store(Transaction $transaction, Collection $sales): Collection;

    /**
     * Get total price of sales.
     *
     * @param Collection $sales
     * @return float
     */
    public function sum(Collection $sales): float;

    /**
     * Revert sales form transaction.
     *
     * @param Sale $sale
     * @return bool
     */
    public function delete(Sale $sale): bool;
}
