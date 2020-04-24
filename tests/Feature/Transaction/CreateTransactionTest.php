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

        $this->generateSales();
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
            'Transactions price is not equal to total sales amount.'
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
     * Covert product to sales.
     *
     * @param object $item
     * @return array
     */
    protected function itemToSale(object $item): array
    {
        return [
            'id' => $item->id,
            'type' => get_class($item),
            'quantity' => $quantity = rand(1, 10),
            'price' => (float) ($item->discount ?? $item->price) * $quantity,
        ];
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
     * Get new attachments
     *
     * @return array
     */
    protected function generateAttachments(): array
    {
        return [
            UploadedFile::fake()->image('t-shirt-print.png', 1*1000),
            UploadedFile::fake()->create('resume.docx', '1.9*1000'),
            UploadedFile::fake()->create('application.pdf', '1.2*1000'),
            UploadedFile::fake()->create('video.mp4', 50*1000),
            UploadedFile::fake()->create('music.gif', 5*1000),
        ];
    }

    /**
     * Generate sales for a transaction.
     *
     * @return void
     */
    protected function generateSales()
    {
        $this->sales = collect()
            ->merge(factory(Product::class)->times(10)->create())
            ->map(function(object $item) {
                return $this->itemToSale($item);
            });
    }

    /**
     * Assert if attachments are upload in local storage.
     *
     * @param array $attachments
     */
    protected function assertAttachmetsExists(Collection $attachments = null)
    {
        $this->assertFalse(is_null($attachments), 'Transaction attachments are not stored in storage');

        $attachments->map(function(Attachments $attachments) {
            Storage::assertExists($attachments->path);
        });
    }
}
