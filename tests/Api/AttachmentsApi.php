<?php


namespace Tests\Api;


use App\Attachments;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

trait AttachmentsApi
{
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
     * @param Collection $attachments
     */
    protected function assertAttachmetsExists(Collection $attachments = null): void
    {
        $this->assertFalse(is_null($attachments), 'TransactionLogic attachments are not stored in storage.');

        $attachments->map(function(Attachments $attachments) {
            Storage::assertExists($attachments->path);
        });
    }
}
