<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\TestResponse;

class CreateUserAttachmentsTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        auth()->login($this->getUser());
    }

    /**
     * Try to create user attachments.
     *
     * @return void
     */
    public function testCreateUserAttachements()
    {
        $data = [
            'attachments' => $this->generateAttachments()
        ];

        $this->addUserAttachments($data)
            ->assertOk();

        $this->assertAttachmetsExists(auth()->user()->attachments);
    }

    /**
     * Try to create user attachment with empty data.
     */
    public function testCreateUserAttachmentsWithEmptyData()
    {
        $this->addUserAttachments()
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['attachments']);
    }

    /**
     * Try to create user attachment with invalid attachment.
     */
    public function testCreateUserAttachmentWithInvalidFile()
    {
        $data = [
            'attachments' => [
                UploadedFile::fake()->createWithContent('index.php', '<?php echo "Laravel"; ?>')
            ]
        ];

        $this->addUserAttachments($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['attachments.0']);
    }

    /**
     * Make request to add user attachments to storage.
     *
     * @param array $data
     * @return TestResponse
     */
    protected function addUserAttachments(array $data = []): TestResponse
    {
        return $this->json('POST', 'api/user/attachments/create', $data);
    }
}
