<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Role;
use App\UsersRoles;
use App\Logic\UserLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddRoleRequest;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * @var UserLogic
     */
    protected $user;

    /**
     * RoleController constructor.
     *
     * @param UserLogic $user
     */
    public function __construct(UserLogic $user)
    {
        $this->user = $user;
    }

    /**
     * Add role to a user.
     *
     * @param AddRoleRequest $validator
     * @return JsonResponse
     */
    public function add(User $user, AddRoleRequest $validator): JsonResponse
    {
        if($this->user->hasRole($user, $role = $validator->get('role'))) {
            return $this->roleErrorResponse();
        }

        $this->user->addRole($user, $role);

        return response()->json(['message' => 'User role has been added.']);
    }

    /**
     * User already has the role response error.
     *
     * @return JsonResponse
     */
    protected function roleErrorResponse(): JsonResponse
    {
        return response()->json(
            ['message' => 'User role has been added.'], JsonResponse::HTTP_BAD_REQUEST
        );
    }
}
