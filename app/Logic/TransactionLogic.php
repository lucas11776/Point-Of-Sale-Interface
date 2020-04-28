<?php /** @noinspection ALL */


namespace App\Logic;

use App\Sale;
use App\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as DbCollection;

class TransactionLogic implements TransactionInterface
{
    /**
     * Sales logic class.
     *
     * @var SalesLogic
     */
    protected $sales;

    /**
     * TransactionLogic constructor.
     *
     * @param SalesLogic $salesLogic
     */
    public function __construct(SalesLogic $salesLogic)
    {
        $this->sales = $salesLogic;
    }

    /**
     * @inheritDoc
     */
    public function checkout(array $attributes, array $sales): Transaction
    {
        $price = ['price' => $this->sales->sum(collect($sales))];
        $transaction = $this->create(array_merge($attributes, $price));
        $transaction->sale = $this->sales->store($transaction, collect($sales));

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
     * @inheritDoc
     */
    public function delete(Transaction $transaction): bool
    {
        // TODO: Implement delete() method.
    }

    /**
     * Create new transaction record in storage.
     *
     * @param array $data
     * @return Transaction
     */
    protected function create(array $data): Transaction
    {
        return Transaction::create([
            'price' => $data['price'],
            'payed' => $data['payed'] ?? $data['price'],
            'customer_id' => $data['customer'] ?? null,
            'employee_id' => $data['employee'] ?? null,
            'device' => request()->ip(),
        ]);
    }
}
