<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\UpdateUserRequest;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified user.
     *
     * @param AuthController $user
     * @return JsonResponse
     */
    public function show(AuthController $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserRequest $validator
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $validator): JsonResponse
    {
        auth()->user()->update($validator->validated());

        return response()->json(['message' => 'User account has been updated.']);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param AuthController $user
     * @return JsonResponse
     */
    public function destroy(AuthController $user): JsonResponse
    {
        return response()->json($user);
    }
}
