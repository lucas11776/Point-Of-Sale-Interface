<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display the specified user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Check if user role exist.
     *
     * @param string $role
     * @return array
     */
    public function role(string $role)
    {
        $role = auth()->user()->roles()->where('name', $role)->first();

        return ['result' => $role ? true : false];
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
