<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\Transactions;

use App\Sale;
use App\Order;
use App\Transaction;
use App\Attachments;
use App\Traits\AttachmentTrait;
use App\Http\Requests\SaleRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachmentsRequest;
use App\Http\Requests\TransactionRequest;
use App\Logic\TransactionLogic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use AttachmentTrait;

    /**
     * TransactionLogic repository.
     *
     * @var TransactionLogic
     */
    protected $transaction;

    /**
     * TransactionController constructor.
     *
     * @param TransactionLogic $transaction
     */
    public function __construct(TransactionLogic $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created transaction in storage.
     *
     * @param TransactionRequest $transactionValidator
     * @param SaleRequest $salesValidator
     * @return JsonResponse
     */
    public function store(
        TransactionRequest $transactionValidator,
        SaleRequest $salesValidator,
        OrderRequest $orderValidator,
        AttachmentsRequest $attachmentsValidator): JsonResponse
    {
        $transaction = $this->createTransaction(
            $transactionValidator->get('transaction') ?? [], $salesValidator->get('sales')
        );

        if($order = $orderValidator->validated()['order'] ?? null) {
            $order = $this->createOrder($transaction, $order);
        }

        if($attachments = $attachmentsValidator->validated()['attachments'] ?? null) {
            $this->storeAttachments($order ?? $transaction, $attachments);
        }

        return response()->json([
            'message' => 'TransactionLogic has been proccesed.',
            'data' => [
                'transaction' => $transaction
            ]
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionLogic $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Create new order in database.
     *
     * @param array $data
     * @return Order
     */
    protected function createOrder(Transaction $transaction, array $data): Order
    {
        return Order::create([
            'orderizable_type' => get_class($transaction),
            'customer_id' => $transaction->customer_id,
            'orderizable_id' => $transaction->id,
            'user_id' => auth()->user()->id,
            'deadline' => $data['deadline'],
            'message' => $data['message'],
            'status' => $data['status'] ?? 'waiting'
        ]);
    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return TransactionLogic
     */
    private function createTransaction(array $transaction = [], array $sales): Transaction
    {
        return $this->transaction->checkout($this->mergeEmployeeId($transaction), $sales);
    }

    /**
     * Merge current user id as employee.
     *
     * @param array $data
     * @return array
     */
    private function mergeEmployeeId(array $data): array
    {
        return array_merge($data, ['employee' => auth()->user()->id]);
    }
}
