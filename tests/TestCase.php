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
use Tests\Api\ApiTest;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, ApiTest;

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
}
