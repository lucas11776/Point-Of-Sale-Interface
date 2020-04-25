<?php /** @noinspection ALL */

namespace Tests;

use App\Attachments;
use App\Customer;
use App\Product;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /**
     * Get regular user account.
     *
     * @return User
     */
    protected function getUser(): User
    {
        return factory(User::class)->create();
    }

    /**
     * Get a random customer account.
     *
     * @return Customer
     */
    protected function getCustomer(): Customer
    {
        $user = factory(User::class)->create();

        return factory(Customer::class)->create($user->toArray());
    }

    /**
     * Get a random administrator account.
     *
     * @return User
     */
    protected function getAdministrator(): User
    {
        return $this->getUserByRole('administrator');
    }

    /**
     * Get a random employee account.
     *
     * @return User
     */
    protected function getEmployee(): User
    {
        return $this->getUserByRole('employee');
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
     * Assert if attachments are upload in local storage.
     *
     * @param array $attachments
     */
    protected function assertAttachmetsExists(Collection $attachments = null): void
    {
        $this->assertFalse(is_null($attachments), 'Transaction attachments are not stored in storage.');

        $attachments->map(function(Attachments $attachments) {
            Storage::assertExists($attachments->path);
        });
    }

    /**
     * Generate sales for a transaction.
     *
     * @return void
     */
    protected function generateSales()
    {
        return collect()
            ->merge(factory(Product::class)->times(rand(1,10))->create())
            ->map(function(object $item) {
                return $this->itemToSale($item);
            });
    }

    /**
     * Create new user with the given role.
     *
     * @param string $role
     * @return User
     */
    private function getUserByRole(string $role): User
    {
        $role = Role::where('name', $role)->first();
        $user = factory(User::class)->create();

        $user->addRole($role);

        return $user;
    }
}
