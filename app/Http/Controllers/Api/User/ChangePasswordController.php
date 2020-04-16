<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Change user password.
     *
     * @param ChangePasswordRequest $validator
     * @return JsonResponse
     */
    public function change(ChangePasswordRequest $validator): JsonResponse
    {
        auth()->user()->update(['password' => Hash::make($validator->validated()['password'])]);

        return response()->json(['messages' => 'Password has been changed.']);
    }
}
