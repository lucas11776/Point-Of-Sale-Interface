<?php

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json($request);
    }

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
     * @param Request $request
     * @param AuthController $user
     * @return JsonResponse
     */
    public function update(Request $request, AuthController $user): JsonResponse
    {
        return response()->json($user);
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
