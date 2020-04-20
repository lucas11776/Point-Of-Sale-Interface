<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\TestResponse;

class UploadProfilePictureTest extends TestCase
{
    /**
     * Try to upload valid profile picture.
     */
    public function testUploadProfilePicture()
    {
        auth()->login($user = $this->getUser());

        $data = [
            'image' => $file = UploadedFile::fake()->image('test_image.png')->size(1.5*1000)
        ];

        $this->upload($data)
            ->assertOk();

        Storage::disk('public')
            ->assertExists($file->hashName());

        $this->assertTrue(
            'public/' . $file->hashName() == $user->image->path,
            'User profile picture has been uploaded but user image path has not changed.'
        );
    }

    /**
     * Try to upload not allowed image extension
     */
    public function testUploadProfilePictureWithInvalidExtension()
    {
        auth()->login($user = $this->getUser());

        $data = ['image' => $file = UploadedFile::fake()->image('document.pdf')->size(1.5*1000)];

        $this->upload($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Try to upload image that is bigger then allowed file file upload.
     */
    public function testUploadProfilePictureWithMaxOutSize()
    {
        auth()->login($user = $this->getUser());

        $data = ['image' => $file = UploadedFile::fake()->image('test_image.png')->size(3*1000)];

        $this->upload($data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Try to upload image that is bigger then allowed file file upload.
     */
    public function testUploadProfilePictureWithEmptyImage()
    {
        auth()->login($user = $this->getUser());

        $this->upload([])
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Make profile picture upload request to application.
     *
     * @param array $data
     * @return TestResponse
     */
    public function upload(array $data): TestResponse
    {
        return $this->json('PATCH', 'api/user/upload', $data);
    }
}
