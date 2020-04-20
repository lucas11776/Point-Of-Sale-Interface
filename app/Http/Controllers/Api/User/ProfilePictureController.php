<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProfilePictureRequest;
use App\Image;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfilePictureController extends Controller
{
    /**
     * Upload user profile picture.
     *
     * @param UploadProfilePictureRequest $validator
     * @return JsonResponse
     */
    public function upload(UploadProfilePictureRequest $validator): JsonResponse
    {
        $this->changeUserImage($validator->validated()['image']);

        return response()->json(['message' => 'Profile picture has been uploaded.']);
    }

    /**
     * Change user profile image.
     *
     * @param UploadedFile $image
     * @return bool
     */
    private function changeUserImage(UploadedFile $image): bool
    {
        $path = Storage::put('public', $image);

        Storage::delete($user = auth()->user());

        return $user->image->update(['path' => $path, 'url' => url($path)]);
    }
}
