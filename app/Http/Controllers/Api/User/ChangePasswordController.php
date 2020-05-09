<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Logic\UserLogic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * @var UserLogic
     */
    protected $user;

    /**
     * ChangePasswordController constructor.
     *
     * @param UserLogic $user
     */
    public function __construct(UserLogic $user)
    {
        $this->user = $user;
    }

    /**
     * Change user password.
     *
     * @param ChangePasswordRequest $validator
     * @return JsonResponse
     */
    public function change(ChangePasswordRequest $validator): JsonResponse
    {
        $this->user->changePassword(auth()->user(), $validator->get('password'));

        return response()->json(['messages' => 'Password has been changed.']);
    }
}
