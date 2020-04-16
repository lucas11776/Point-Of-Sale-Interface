<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProfilePictureRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $path = Storage::put('public', $image = $validator->validated()['image']);

        $this->changeUserImage($path);

        return response()->json(['message' => 'Profile picture has been uploaded.']);
    }

    /**
     * Change user profile image.
     *
     * @param string $path
     * @return bool
     */
    protected function changeUserImage(string $path): bool
    {
        Storage::delete($user = auth()->user());

        return $user->image->update(['path' => $path, 'url' => url($path)]);
    }
}
