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
        Storage::fake('public');

        $token = auth()->login($user = User::first());
        $file = UploadedFile::fake()->image('test_image.png')->size(1.5*1000);
        $data = ['image' => $file];

        $this->upload($data, $token)
            ->assertOk();

        Storage::disk('public')->assertMissing($file->hashName());

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
        Storage::fake('public');

        $token = auth()->login($user = User::first());
        $data = ['image' => $file = UploadedFile::fake()->image('document.pdf')->size(1.5*1000)];

        $this->upload($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Try to upload image that is bigger then allowed file file upload.
     */
    public function testUploadProfilePictureWithMaxOutSize()
    {
        Storage::fake('public');

        $token = auth()->login($user = User::first());
        $data = ['image' => $file = UploadedFile::fake()->image('test_image.png')->size(3*1000)];

        $this->upload($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Try to upload image that is bigger then allowed file file upload.
     */
    public function testUploadProfilePictureWithEmptyImage()
    {
        Storage::fake('public');

        $token = auth()->login($user = User::first());
        $data = ['image' => ''];

        $this->upload($data, $token)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['image']);
    }

    /**
     * Make profile picture upload request to application.
     *
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    public function upload(array $data, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('PATCH', 'api/user/upload', $data);
    }
}
