<?php /** @noinspection ALL */

namespace Tests\Feature\Transaction;

use App\Product;
use App\UsersRoles;
use Tests\TestCase;
use App\Attachments;
use App\Transaction;
use Faker\Factory as Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTransactionTest extends TestCase
{
    /**
     * Generated product to test for testing.
     *
     * @var Collection
     */
    protected $sales;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        auth()->login($this->getEmployee());

        $this->sales = $this->generateSales();
    }

    /**
     * Try to create a new transaction in storage.
     */
    public function testCreateTransaction()
    {
        $data = [
            'transaction' => $this->getTransaction(),
            'sales' => $this->sales,
            'order' => $this->generateOrder(),
            'attachments' => $this->generateAttachments()
        ];

        $this->createTransaction($data)
            ->assertOk()
            ->assertJsonStructure(['message']);

        $attachements = Transaction::orderBy('id', 'DESC')->first()->attachements;

        $this->assertAttachmetsExists($attachements);
    }

    /**
     * Try to create a new transaction with empty transaction in storage.
     */
    public function testCreateTransactionWithOnlySales()
    {
        $data = [
            'sales' => $this->sales,
        ];

        $this->createTransaction($data)
            ->assertOk()
            ->assertJsonStructure(['message']);

        $transaction = Transaction::orderBy('id', 'DESC')->first();

        $this->assertTrue(
            $transaction->price == $this->sum(),
            'Transaction price is not equal to total sales sum.'
        );
    }

    /**
     * Try create a new transaction and check if product stock count has changed
     */
    public function testCreateTransactionAndCheckProductQuantityHasChanged()
    {
        $data = [
            'sales' => $this->sales,
        ];
        $sale = $this->productsFilter($data['sales'])->first();
        $product = Product::where('id', $sale['id'])->first()->toArray();

        $this->createTransaction($data)
            ->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertEquals(
            $sale['quantity'] >= $product['in_stock'] ? 0 : $product['in_stock'] - $sale['quantity'],
            Product::find($sale['id'])->in_stock,
            'The number of product in stock does not match expected number after transaction'
        );
    }

    /**
     * Try to create a new transaction with attachments.
     */
    public function testCreateTransactionWithOnlySalesAndAttachments()
    {
        $data = [
            'sales' => $this->sales,
            'attachments' => $this->generateAttachments()
        ];

        $this->createTransaction($data)
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    /**
     * Try to create a new transaction with empty data.
     */
    public function testCreateTransactionWithEmptyData()
    {
        $this->createTransaction()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['sales']);
    }

    /**
     * Try to create a new transction with invalid order data.
     */
    public function testCreateTransactionWithInvalidOrderDateDeadline()
    {
        $order = $this->generateOrder();

        $order['deadline'] = '89 MAYA 20K2 a0:00pm';

        $data = [
            'transaction' => $this->getTransaction(),
            'sales' => $this->sales,
            'order' => $order,
        ];

        $this->createTransaction($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['order.deadline']);
    }

    /**
     * Try to create transaction with invalid sale id.
     */
    public function testCreateTransactionWithInvalidSaleId()
    {
        $sales = $this->sales->map(function(array $sale, int $index) {
            if($index == 0) {
                $sale['id'] = rand(50, 100);
            }

            return $sale;
        });

        $data = [
            'sales' => $sales,
        ];

        $this->createTransaction($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['sales.0']);
    }

    /**
     * Try to create transaction with invalid sale type.
     */
    public function testCreateTransactionWithInvalidSaleType()
    {
        $sales = $this->sales->map(function(array $sale, int $index) {
            if($index == 0) {
                $sale['type'] = 'App/Testing';
            }

            return $sale;
        });

        $data = [
            'sales' => $sales,
        ];

        $this->createTransaction($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['sales.0']);
    }

    /**
     * Try to create transaction with invalid sale id.
     */
    public function testCreateTransactionWithInvalidAttachmentType()
    {
        $data = [
            'sales' => $this->sales,
            'attachments' => [
                UploadedFile::fake()->createWithContent('app.php', '<?php echo "Hello World"; ?>')
            ]
        ];

        $this->createTransaction($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['attachments.0']);
    }

    /**
     * Try to create a new transaction with max out file size.
     */
    public function testCreateTransactionWithMaxOutAttachment()
    {
        $data = [
            'sales' => $this->sales,
            'attachments' => [
                UploadedFile::fake()->create('video.mp4', 200 * 1000)
            ]
        ];

        $this->createTransaction($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['attachments.0']);
    }

    /**
     * Make request to create a transaction in application.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function createTransaction(array $data = []): TestResponse
    {
        return $this->withHeader('user-agent', Faker::create()->userAgent)
            ->json('POST', 'api/transactions/checkout', $data);
    }

    /**
     * Get new transaction.
     *
     * @return array
     */
    protected function getTransaction(): array
    {
        return [
            'customer' => $this->getCustomer()->id,
            'price' => $this->sum(),
            'payed' => round($this->sum() + rand(10, 200))
        ];
    }

    /**
     * Get all product sum.
     *
     * @return float
     */
    protected function sum(): float
    {
        return $this->sales->sum(function(array $item) {
            return (float) (($item['discount'] ?? $item['price']) * $item['quantity']);
        });
    }

    /**
     * Get a new order.
     *
     * @return array
     */
    protected function generateOrder(): array
    {
        return [
            'deadline' => date('l d M Y h:ma', time() + (24 * (60*60))),
            'message' => Faker::create()->sentence(10, 50)
        ];
    }

    /**
     * Filter products only.
     *
     * @param Collection $sales
     * @return Collection
     */
    protected function productsFilter(Collection $sales): Collection
    {
        return $sales->filter(function($model) {
            return $model['type'] == Product::class;
        });
    }
}
