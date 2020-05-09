<?php

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Logic\AuthenticationLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @var AuthenticationLogic
     */
    private $authentication;

    /**
     * UserController constructor.
     *
     * @param AuthenticationLogic $authentication
     */
    public function __construct(AuthenticationLogic $authentication)
    {
        $this->authentication = $authentication;
    }

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
     * @return JsonResponse
     */
    public function role(string $role): JsonResponse
    {
        return response()->json(['result' => $this->authentication->roleExists(auth()->user(), $role)]);
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
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        return response()->json($user);
    }
}
